<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use MauticPlugin\WeixinBundle\Entity\Weixin;

class Application
{
    private $configs;
    private $application;

    public function __construct($configs)
    {
        $this->configs = $configs;
        $this->application = new \Chunhei2008\EasyOpenWechat\Foundation\Application($configs);
    }

    public function handleAuth()
    {
        return $this->application->auth->handle()->send();
    }

    public function getLoginUrl()
    {
        return $this->application->login->getLoginPage();
    }

    public function createWeixin($code)
    {
        $auth_info = $this->application->authorization->setAuthorizationCode($code)->getAuthorizationInfo();
        $authorizer_info = $this->application->authorizer->getAuthorizerInfo($auth_info->authorizer_appid);

        $weixin = new Weixin();

        $weixin->setAuthorizerAppId($auth_info->authorizer_appid);
        $weixin->setAuthorizerAccessToken($auth_info->authorizer_access_token);
        $weixin->setAuthorizerRefreshToken($auth_info->authorizer_refresh_token);
        $weixin->setAccountName($authorizer_info->nick_name);
        $weixin->setIcon($authorizer_info->head_img);
        $weixin->setType($authorizer_info->service_type_info['id']);
        $weixin->setVerified($authorizer_info->verify_type_info['id']);

        return $weixin;
    }

}