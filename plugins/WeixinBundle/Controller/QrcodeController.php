<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Qrcode;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use MauticPlugin\WeixinBundle\Form\Type\QrcodeType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class QrcodeController extends BaseController
{
    /**
     * @Route("/")
     */
    public function indexAction($page = 1)
    {

        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $count = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->getCount();
        $items = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->findBy(
            [], [], 10, 10 * ($page - 1)
        );

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'page' => $page,
                'items' => $items,
                'totalItems' => $count,
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function newAction(Request $request)
    {
        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $weixins = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findBy([
            'owner' => $this->getUser(),
            'type' => 2,
        ]);
        $qrcode = new Qrcode();
        $form = $this->createForm(QrcodeType::class, $qrcode, [
            'action' => $this->generateUrl('mautic_weixin_qrcode_new'),
            'weixins' => $weixins,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($qrcode->getMessage()->getMsgType() != 'none') {
                $this->get('weixin.helper.message')->handleMessageImage($currentWeixin, $qrcode->getMessage(), $form->get('message')->get('file')->getData());
                $em->persist($qrcode->getMessage());
            } else {
                $qrcode->setMessage(null);
            }
            $index = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->getNextIndex($qrcode->getWeixin());
            $qrcode->setNb($index);
            $this->get('weixin.api')->uploadQrcode($qrcode);
            $qrcode->setCreateTime(new \DateTime());
            $em->persist($qrcode);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_qrcode');
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:new.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function showAction($id)
    {
        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);


        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'qrcode' => $qrcode,
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:show.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function downloadAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);

        if ($qrcode->getImage()) {
            $response = new BinaryFileResponse($this->getParameter('kernel.root_dir') . '/../' . $qrcode->getImage());
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            return $response;
        } else {
            $filename = md5(uniqid()) . '.png';
            (new \Endroid\QrCode\QrCode($qrcode->getUrl()))->writeFile($this->getParameter('kernel.root_dir') . '/../qrcode/' . $filename);
            $qrcode->setImage('qrcode/' . $filename);
            $em->flush();
            $response = new BinaryFileResponse($this->getParameter('kernel.root_dir') . '/../' . $qrcode->getImage());
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            return $response;
        }
    }

}
