<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;

class AutoResController extends AbstractFormController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {

        if(!$this->get('session')->get('current_weixin')) {
            $weixin = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findOneByOwner($this->getUser());
            $this->get('session')->set('current_weixin', $weixin);
        }

        $currentWeixin = $this->get('session')->get('current_weixin');

        $followedMessageForm = $this->createForm(FollowedMessageType::class, $currentWeixin);

        $keywordMessageForm = $this->createForm(KeywordMessageType::class, $currentWeixin);

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'followedMessageForm' => $followedMessageForm->createView(),
                'keywordMessageForm' => $this->setFormTheme($keywordMessageForm, 'WeixinBundle:AutoRes:index.html.php','WeixinBundle:FormTheme\Rule'),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }
}
