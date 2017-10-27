<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;

class OpenController extends AbstractFormController
{

    public function authAction()
    {

        return $this->get('weixin.open_application')->handleAuth();
    }

    public function oauthLoginAction()
    {
        $url = $this->get('weixin.open_application')->getLoginUrl();
        return $this->redirect($url);
    }
}
