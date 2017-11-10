<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-11-9
 * Time: 下午11:00
 */

namespace MauticPlugin\WeixinBundle\Subscriber;

use Mautic\LeadBundle\Entity\Lead;
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
        $message = $event->getMsg();
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if(!$lead) {
            $lead = new Lead();
            $lead->set
            $userInfos = $this->api->getUserInfos($message['FromUserName']);
        }
    }

    public function onUnsubscribe(Event $event)
    {

    }
}
