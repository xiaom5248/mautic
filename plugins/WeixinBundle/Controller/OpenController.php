<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Chunhei2008\EasyOpenWechat\Support\Log;
use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use Symfony\Component\HttpFoundation\Request;

class OpenController extends AbstractFormController
{

    public function authAction(Request $request)
    {
        Log::debug(json_encode($request->query->all()));
        Log::debug(json_encode($request->request->all()));
        return $this->get('weixin.open_application')->handleAuth();
    }

    public function oauthLoginAction()
    {
        $url = $this->get('weixin.open_application')->getLoginUrl();
        return $this->redirect($url);
    }
}
