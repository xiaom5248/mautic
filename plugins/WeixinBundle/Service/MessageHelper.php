<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Entity\Rule;
use MauticPlugin\WeixinBundle\Entity\Weixin;

class MessageHelper
{
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function handleMessageImage(Message $message, $file)
    {
        if (in_array($message->getMsgType(), [Message::MSG_TYPE_IMG, Message::MSG_TYPE_IMGTEXT])) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->rootDir . '/../uploads',
                $fileName
            );
            $message->setImage('uploads/' . $fileName);
        }
    }

    public function getMessageRes(Weixin $weixin, $msg)
    {
        foreach ($weixin->getRules() as $rule)
        {
            foreach ($rule->getKeywords() as $keyword) {
                if($keyword->getType() == Rule::RULE_TYPE_COMPLET && $keyword->getKeyword() == $msg) {
                    return $keyword->getMessage();
                }

                if($keyword->getType() == Rule::RULE_TYPE_LIKE && strpos($msg, $keyword->getKeyword()) !== false) {
                    return $keyword->getMessage();
                }
            }
        }
    }
}