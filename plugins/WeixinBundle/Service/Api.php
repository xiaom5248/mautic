<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use MauticPlugin\WeixinBundle\Entity\Menu;
use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Entity\Weixin;

class Api
{
    private $configs;

    public function __construct($configs)
    {
        $this->configs = $configs;
    }

    public function updateMenu(Weixin $weixin)
    {
        $app = new \EasyWeChat\Foundation\Application($this->options);
        $menu = $app->menu;

        $buttons = [];
        foreach ($weixin->getMenus() as $menu) {
            /* @var $menu Menu */
            if(count($menu->getItems()) == 0){
                $buttons[] = $this->buildButton($menu);
            }else{

                $button = [
                    'name' => $menu->getName(),
                    'sub_button' => [],
                ];
                foreach ($menu->getItems() as $item) {
                    $button['sub_button'][] = $this->buildButton($item);
                }
                $buttons[] = $button;
            }
        }

        $menu->add($buttons);
    }

    private function buildButton($menu)
    {
        if($menu->getType() == Menu::MENU_TYPE_MESSAGE ){
            $button = [
                'type' => 'click',
                'name' => $menu->getName(),
                'key' => $menu->getMessage()->getId(),
            ];
        }
        if($menu->getType() == Menu::MENU_TYPE_URL ){
            $button = [
                'type' => 'view',
                'name' => $menu->getName(),
                'url' => $menu->getUrl(),
            ];
        }

        return $button;
    }

    public function sendMessage(Message $message)
    {
        switch ($message->getMsgType()){
            case Message::MSG_TYPE_TEXT:
                $msg = new Text();
                $msg->content = $message->getContent();
                break;
            case Message::MSG_TYPE_IMG:
                $msg = new Image();
                $msg->media_id = $message->getImageId();
                break;
            case Message::MSG_TYPE_IMGTEXT:
                $msg = new News();
                $msg->title = $message->getArticleTitle();
                $msg->description  = $message->getArticleDesc();
                $msg->url  = $message->getArticleUrl();
                $msg->image  = $message->getImageUrl();
                break;
        }

        $app = new \EasyWeChat\Foundation\Application($this->options);
        $server = $app->server;
        $server->setMessageHandler(function () use ($msg) {
            return $msg;
        });
    }

}