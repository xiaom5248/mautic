<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-11-9
 * Time: 下午11:00
 */

namespace MauticPlugin\WeixinBundle\Subscriber;

use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\WeixinBundle\Entity\LeadWeixinAction;
use MauticPlugin\WeixinBundle\Event\Event;
use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WeixinSubscriber implements EventSubscriberInterface
{

    private $em;
    private $api;
    private $leadModel;

    public function __construct($doctrine, $api, $leadModel)
    {
        $this->api = $api;
        $this->em = $doctrine->getManager();
        $this->leadModel = $leadModel;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::WEIXIN_SUBSCRIBE => ['onSubscribe', 100],
            Events::WEIXIN_UNSUBSCRIBE => ['onUnSubscribe', 100],
        ];
    }

    public function onSubscribe(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        if(!$lead) {
            $lead = new Lead();

            $userInfos = $this->api->getUserInfos($weixin, $message['FromUserName']);

            $this->leadModel->setFieldValues($lead, [
                'wechat_openid' => $userInfos['openid'],
                'wechat_nickname' => $userInfos['nickname'],
                'city' => $userInfos['city'],
                'province' => $userInfos['province'],
            ], true);

            $lead->setDateIdentified(new \DateTime());

            $this->leadModel->saveEntity($lead);

        }

        $this->createWeixinAction($weixin, $lead, $message, Events::WEIXIN_SUBSCRIBE);
    }

    public function onUnsubscribe(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        $this->createWeixinAction($weixin, $lead, $message, Events::WEIXIN_UNSUBSCRIBE);
    }

    private function createWeixinAction($weixin, $lead, $message, $event)
    {
        $leadWeixinAction = new LeadWeixinAction();
        $leadWeixinAction->setWeixin($weixin);
        $leadWeixinAction->setContact($lead);
        $leadWeixinAction->setMessage($message);
        $leadWeixinAction->setEvent($event);
        $leadWeixinAction->setTime(new \DateTime());

        $this->em->persist($leadWeixinAction);
        $this->em->flush();
    }
}

