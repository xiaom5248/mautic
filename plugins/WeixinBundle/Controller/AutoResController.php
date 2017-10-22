<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use Symfony\Component\HttpFoundation\Request;

class AutoResController extends BaseController
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {

        $module = $request->query->get('m', 'followed');

        $currentWeixin = $this->getCurrentWeixin();

        $followedMessageForm = $this->createForm(FollowedMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_followed_message'),
        ]);
        $keywordMessageForm = $this->createForm(KeywordMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_keyword_message'),
        ]);

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'module' => $module,
                'followedMessageForm' => $this->setFormTheme($followedMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
                'keywordMessageForm' => $this->setFormTheme($keywordMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function followedMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentWeixin = $this->getCurrentWeixin();
        if (!$currentWeixin->getFollowedMessage()) {
            $message = new Message();
            $currentWeixin->setFollowedMessage($message);
        }

        $followedMessageForm = $this->createForm(FollowedMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_followed_message'),
        ]);
        $keywordMessageForm = $this->createForm(KeywordMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_keyword_message'),
        ]);
        $followedMessageForm->handleRequest($request);

        if ($followedMessageForm->isSubmitted() && $followedMessageForm->isValid()) {

            if (in_array($currentWeixin->getFollowedMessage()->getType(), [Message::MSG_TYPE_IMG, Message::MSG_TYPE_IMGTEXT])) {
                $file = $currentWeixin->getFollowedMessage()->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('kernel.root_dir'). '/../uploads',
                    $fileName
                );
                $currentWeixin->getFollowedMessage()->setImage($fileName);
            }

            $em->persist($currentWeixin->getFollowedMessage());
            $em->persist($currentWeixin);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_auto_res', ['m' => 'followed']);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'module' => 'followed',
                'followedMessageForm' => $this->setFormTheme($followedMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
                'keywordMessageForm' => $this->setFormTheme($keywordMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function keywordAction(Request $request)
    {
        $currentWeixin = $this->getCurrentWeixin();

        $followedMessageForm = $this->createForm(FollowedMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_followed_message'),
        ]);
        $keywordMessageForm = $this->createForm(KeywordMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_keyword_message'),
        ]);

        $keywordMessageForm->handleRequest($request);

        if ($keywordMessageForm->isSubmitted() && $keywordMessageForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mautic_weixin_auto_res', ['m' => 'keyword']);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'module' => 'keyword',
                'followedMessageForm' => $this->setFormTheme($followedMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
                'keywordMessageForm' => $this->setFormTheme($keywordMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }
}
