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
    public function indexAction($page)
    {

        $currentWeixin = $this->getCurrentWeixin();
        $articles = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\News')->findBy([
            'weixin' => $currentWeixin,
        ], [], 10, 10 * ($page - 1));
        $count = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\News')->getCount($currentWeixin);

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'page' => $page,
                'items' => $articles,
                'totalItems' => $count,
            ],
            'contentTemplate' => 'WeixinBundle:Article:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function syncAction()
    {
        $currentWeixin = $this->getCurrentWeixin();
        $this->get('weixin.api')->syncArticles($currentWeixin);

        return $this->redirectToRoute('mautic_weixin_article');
    }
}
