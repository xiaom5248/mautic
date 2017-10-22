<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;

class ArticleController extends BaseController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {

        $currentWeixin = $this->getCurrentWeixin();

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }
}
