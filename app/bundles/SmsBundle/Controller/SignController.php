<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-14
 * Time: 14:45
 */

namespace Mautic\SmsBundle\Controller;


use Mautic\CoreBundle\Controller\AbstractFormController;
use Mautic\LeadBundle\Controller\EntityContactsTrait;
use Mautic\SmsBundle\Entity\Sign;
use Symfony\Component\HttpFoundation\JsonResponse;

class SignController extends AbstractFormController
{
    use EntityContactsTrait;

    /**
     * @param int $page
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        /** @var \Mautic\SmsBundle\Model\SmsModel $model */
        $model = $this->getModel('sign');


        //set some permissions
        $permissions = $this->get('mautic.security')->isGranted(
            [
                'sms:smses:viewown',
                'sms:smses:viewother',
                'sms:smses:create',
                'sms:smses:editown',
                'sms:smses:editother',
                'sms:smses:deleteown',
                'sms:smses:deleteother',
                'sms:smses:publishown',
                'sms:smses:publishother',
            ],
            'RETURN_ARRAY'
        );

        $session = $this->get('session');


        $limit = $session->get('mautic.sms.sign.limit', $this->coreParametersHelper->getParameter('default_pagelimit'));
        $start = ($page === 1) ? 0 : (($page - 1) * $limit);
        if ($start < 0) {
            $start = 0;
        }

        $search = $this->request->get('search', $session->get('mautic.sms.sign.filter', ''));
        $session->set('mautic.sms.sign.filter', $search);

        $filter = ['string' => $search];

        $orderBy    = $session->get('mautic.sms.sign.orderby', 'e.name');
        $orderByDir = $session->get('mautic.sms.sign.orderbydir', 'DESC');


        $signs = $model->getEntities([
            'start'      => $start,
            'limit'      => $limit,
            'filter'     => $filter,
            'orderBy'    => $orderBy,
            'orderByDir' => $orderByDir,
        ]);

        $count = count($signs);
        if ($count && $count < ($start + 1)) {
            //the number of entities are now less then the current page so redirect to the last page
            if ($count === 1) {
                $lastPage = 1;
            } else {
                $lastPage = (floor($count / $limit)) ?: 1;
            }

            $session->set('mautic.sms.sign.page', $lastPage);
            $returnUrl = $this->generateUrl('mautic_sms_sign_index', ['page' => $lastPage]);

            return $this->postActionRedirect([
                'returnUrl'       => $returnUrl,
                'viewParameters'  => ['page' => $lastPage],
                'contentTemplate' => 'MauticSmsBundle:Sign:index',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_sms_sign_index',
                    'mauticContent' => 'sign',
                ],
            ]);
        }

        $session->set('mautic.sms.sign.page', $page);

        return $this->delegateView([
            'viewParameters'    =>  [
                'searchValue'   =>  $search,
                'items'         =>  $signs,
                'totalItems'    =>  $count,
                'page'          =>  $page,
                'limit'         =>  $limit,
                'tmpl'          =>  $this->request->get('tmpl','index'),
                'permissions'   =>  $permissions,
                'security'      =>  $this->get('mautic.security'),
            ],
            'contentTemplate'   =>  'MauticSmsBundle:Sign:list.html.php',
            'passthroughVars'   =>  [
                'activeLink'    =>  '#mautic_sign_index',
                'mauticContent' =>  'sign',
                'route'         =>  $this->generateUrl('mautic_sms_sign_index',['page' => $page]),
            ],
        ]);
    }

    /***
     * @param Sign $entity
     */
    public function newAction($entity = null)
    {
        $model = $this->getModel('sign');
    }

}
