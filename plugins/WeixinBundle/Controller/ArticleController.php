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

        if(count($currentWeixin->getNews()) == 0){
            $this->get('weixin.api')->initArticles();
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
            ],
            'contentTemplate' => 'WeixinBundle:Article:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }
}
