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
use EasyWeChat\OpenPlatform\Guard;
use MauticPlugin\WeixinBundle\Entity\Menu;
use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Entity\Weixin;

class Api
{
    private $configs;
    private $app;
    private $openPlatform;

    public function __construct($configs)
    {
        $this->configs = $configs;
        $app = new \EasyWeChat\Foundation\Application($configs);
        $this->openPlatform = $app->open_platform;
    }

    public function handleAuth()
    {
        $openPlatform = $this->openPlatform;
        $server = $this->openPlatform->server;
        $server->setMessageHandler(function($event) use ($openPlatform) {
            switch ($event->InfoType) {
                case Guard::EVENT_AUTHORIZED: // 授权成功

                case Guard::EVENT_UPDATE_AUTHORIZED: // 更新授权

                case Guard::EVENT_UNAUTHORIZED: // 授权取消

                    dump($event);
            }
        });
        $response = $server->serve();
        return $response;
    }

    public function getLoginUrl()
    {
        $this->openPlatform->pre_auth->getCode();
        $response = $this->openPlatform->pre_auth->redirect('http://m-demo.linkall.sh.cn/s/weixin/oauth-return');
        $response->getTargetUrl();
        return $response->getTargetUrl();
    }

    public function createWeixin($code)
    {
        $auth_info = $this->openPlatform->getAuthorizationInfo($code);
        $authorizer_info = $this->openPlatform->getAuthorizerInfo($auth_info['authorizer_appid']);

        $weixin = new Weixin();
        $weixin->setAuthorizerAppId($auth_info['authorizer_appid']);
        $weixin->setAuthorizerAccessToken($auth_info['authorizer_access_token']);
        $weixin->setAuthorizerRefreshToken($auth_info['authorizer_refresh_token']);
        $weixin->setAccountName($authorizer_info['nick_name']);
        $weixin->setIcon($authorizer_info['head_img']);
        $weixin->setType($authorizer_info['service_type_info']['id']);
        $weixin->setVerified($authorizer_info['verify_type_info']['id']);

        return $weixin;
    }

    public function setWeixin(Weixin $weixin)
    {
        $this->app = $this->openPlatform->createAuthorizerApplication($weixin->getAuthorizerAppId(), $weixin->getAuthorizerRefreshToken());
    }

    public function updateMenu(Weixin $weixin)
    {
        $this->setWeixin($weixin);

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

        $this->app->menu->add($buttons);
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

        $server = $this->app->server;
        $server->setMessageHandler(function () use ($msg) {
            return $msg;
        });
    }

}