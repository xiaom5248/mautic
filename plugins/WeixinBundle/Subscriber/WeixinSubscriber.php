<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-11-9
 * Time: 下午11:00
 */

namespace MauticPlugin\WeixinBundle\Subscriber;

use MauticPlugin\WeixinBundle\Event\Event;
use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WeixinSubscriber implements EventSubscriberInterface
{

    private $em;
    private $api;

    public function __construct($doctrine, $api)
    {
        $this->api = $api;
        $this->em = $doctrine->getManager();
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

    }

    public function onUnsubscribe(Event $event)
    {

    }
}
