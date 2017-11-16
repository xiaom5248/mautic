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
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
            KernelEvents::TERMINATE => 'onTerminate'
        ];
    }

    public function onSubscribe(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (!empty(key($leads))) {
            $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        }

        $userInfos = $this->api->getUserInfos($weixin, $message['FromUserName']);

        if (!isset($lead)) {
            $lead = new Lead();

            $this->leadModel->setFieldValues($lead, [
                'firstname' => $userInfos['nickname'],
                'wechat_openid' => $userInfos['openid'],
                'wechat_nickname' => $userInfos['nickname'],
                'city' => $userInfos['city'],
                'province' => $userInfos['province'],
                'origin_from' => $weixin->getAccountName().'公众号导入'
            ], true);

            $lead->setDateIdentified(new \DateTime());
        }

        if (false !== strstr($message->EventKey, 'qrscene')) {
            $qrnb = ltrim($message->EventKey, 'qrscene_');
            $qrcode = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->findOneBy([
                'weixin' => $weixin,
                'nb' => $qrnb,
            ]);
            if($qrcode->getLeadField1()) {
                $lead->addUpdatedField($qrcode->getLeadField1(), $qrcode->getLeadField1Value());
            }
            if($qrcode->getLeadField2()) {
                $lead->addUpdatedField($qrcode->getLeadField2(), $qrcode->getLeadField2Value());
            }
        }

        $this->leadModel->saveEntity($lead);
        $time = new \DateTime('@' . $userInfos['subscribe_time']);
        $this->createWeixinAction($weixin, $lead, $message, $time, Events::WEIXIN_SUBSCRIBE);
    }

    public function onUnsubscribe(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        $this->createWeixinAction($weixin, $lead, $message, Events::WEIXIN_UNSUBSCRIBE);
    }

    private function createWeixinAction($weixin, $lead, $message, $time, $event)
    {
        $leadWeixinAction = new LeadWeixinAction();
        $leadWeixinAction->setWeixin($weixin);
        $leadWeixinAction->setContact($lead);
        $leadWeixinAction->setMessage($message);
        $leadWeixinAction->setEvent($event);
        $leadWeixinAction->setTime($time);

        $this->em->persist($leadWeixinAction);
        $this->em->flush();
    }

    public function onTerminate(PostResponseEvent $event)
    {
        $route = $event->getRequest()->get('_route');
        if (in_array($route, ['mautic_weixin_open_auth_return', 'mautic_weixin_sych_users'])) {
            ini_set('max_execution_time', -1);
            $currentWeixinId = $event->getRequest()->get('id');
            $currentWeixin = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->find($currentWeixinId);
            if ($currentWeixin) {
                $this->api->sychUsers($currentWeixin);
            }
        }
    }
}

