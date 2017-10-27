<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

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

}