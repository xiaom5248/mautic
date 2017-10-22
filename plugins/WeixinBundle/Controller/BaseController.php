<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;

abstract class BaseController extends AbstractFormController
{
    /**
     * @Route("/")
     */
    protected function getCurrentWeixin()
    {

        if(!$this->get('session')->get('current_weixin_id')) {
            $weixin = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findOneByOwner($this->getUser());
            if(!$weixin) {
                $this->get('session')->getFlashBag()->add('error', 'weixin.no_available_account');
                return $this->redirectToRoute('mautic_dashboard_index');
            }
            $this->get('session')->set('current_weixin_id', $weixin->getId());
            return $weixin;
        }

        $currentWeixinId = $this->get('session')->get('current_weixin_id');
        $currentWeixin = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->find($currentWeixinId);

        return $currentWeixin;
    }
}
