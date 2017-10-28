<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Chunhei2008\EasyOpenWechat\Support\Log;
use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Weixin;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use Symfony\Component\HttpFoundation\Request;

class OpenController extends AbstractFormController
{

    public function authAction(Request $request)
    {
        return $this->get('weixin.open_application')->handleAuth();
    }

    public function authReturnAction(Request $request)
    {
        $auth_code = $request->query->get('auth_code');

        $weixin = $this->get('weixin.open_application')->createWeixin($auth_code);

        $em = $this->getDoctrine()->getManager();
        $em->persist($weixin);
        $em->flush();

        return $this->redirectToRoute('mautic_config_action', ['tag' => 'weixin']);

    }

    public function oauthLoginAction()
    {
        $url = $this->get('weixin.open_application')->getLoginUrl();
        return $this->redirect($url);
    }
}
