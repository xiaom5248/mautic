<?php

namespace MauticPlugin\WeixinBundle\Controller;

use MauticPlugin\WeixinBundle\Entity\NewsSend;
use MauticPlugin\WeixinBundle\Form\Type\NewsSendScheduleType;
use MauticPlugin\WeixinBundle\Form\Type\NewsSendType;
use Symfony\Component\HttpFoundation\Request;

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

    public function syncAllAction()
    {
        $currentWeixin = $this->getCurrentWeixin();
        $this->get('weixin.api')->syncArticles($currentWeixin);

        return $this->redirectToRoute('mautic_weixin_article');
    }

    public function syncAction($id)
    {
        $news = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\News')->find($id);
        $this->get('weixin.api')->syncArticle($news);
        $this->get('session')->getFlashBag()->set('notice', '更新成功');
        return $this->redirectToRoute('mautic_weixin_article');
    }

    public function sendAction(Request $request, $id)
    {
        $news = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\News')->find($id);
        $currentWeixin = $this->getCurrentWeixin();

        $send = new NewsSend();
        $send->setNews($news);
        $form = $this->createForm(NewsSendType::class, $send);

        $form->handleRequest($request);

        if($form->isValid()) {
            if($send->getSendType() == NewsSend::NEWS_SEND_ALL) {
                $this->get('weixin.api')->sendArticleDirect($send);
            }

            return $this->redirectToRoute('mautic_weixin_article');
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Article:send.html.php',
            'passthroughVars' => [

            ],
        ]);
    }


    public function sendScheduleAction(Request $request, $id)
    {
        $news = $this->getDoctrine()->getRepository('MauticPlugin\WeixinBundle\Entity\News')->find($id);
        $currentWeixin = $this->getCurrentWeixin();

        $send = new NewsSend();
        $send->setNews($news);
        $form = $this->createForm(NewsSendScheduleType::class, $send);

        $form->handleRequest($request);

        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($send);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_article');
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Article:send.html.php',
            'passthroughVars' => [

            ],
        ]);
    }
}
