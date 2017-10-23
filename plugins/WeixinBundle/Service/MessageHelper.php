<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use MauticPlugin\WeixinBundle\Entity\Message;

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
}