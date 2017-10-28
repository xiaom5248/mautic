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
        return $this->get('weixin.api')->handleAuth();
    }

    public function authReturnAction(Request $request)
    {
        $auth_code = $request->query->get('auth_code');

        $weixin = $this->get('weixin.api')->createWeixin($auth_code);

        $weixin->setOwner($this->getUser());
        $weixin->setCreateTime(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($weixin);
        $em->flush();

        return $this->redirectToRoute('mautic_config_action', [
            'tag' => 'weixin',
            'objectAction' => 'edit',
        ]);

    }

    public function oauthLoginAction()
    {
        $url = $this->get('weixin.api')->getLoginUrl();
        return $this->redirect($url);
    }
}
