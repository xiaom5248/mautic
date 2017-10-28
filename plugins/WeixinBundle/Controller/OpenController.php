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
        $auth_info = $this->get('weixin.open_application')->authorization->setAuthorizationCode($auth_code)->getAuthorizationInfo();

        dump($auth_info);

        $em = $this->getDoctrine()->getManager();
        $weixin = new Weixin();

        $weixin->setAccountName($auth_info['nick_name']);
        $weixin->setIcon($auth_info['head_img']);
        $weixin->setType($auth_info['service_type_info']['id']);
        $weixin->setVerified($auth_info['verify_type_info']['id']);

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
