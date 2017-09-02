<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-14
 * Time: 14:45
 */

namespace Mautic\SmsBundle\Controller;


use Mautic\CoreBundle\Controller\AbstractFormController;
use Mautic\CoreBundle\Helper\InputHelper;
use Mautic\LeadBundle\Controller\EntityContactsTrait;
use Mautic\SmsBundle\Entity\Sign;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        $orderBy    = $session->get('mautic.sms.sign.orderby', 's.name');
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
        /** @var \Mautic\SmsBundle\Model\SignModel $model */
        $model = $this->getModel('sign');

        if (!$entity instanceof Sign) {
            $entity = $model->getEntity();
        }

        $method  = $this->request->getMethod();
        $session = $this->get('session');

        if (!$this->get('mautic.security')->isGranted('sms:smses:create')) {
            return $this->accessDenied();
        }

        $page    = $session->get('mautic.sign.page', 1);
        $action  = $this->generateUrl('mautic_sms_sign_action', ['objectAction' => 'new']);

        $updateSelect = ($method == 'POST')
            ? $this->request->request->get('sign[updateSelect]', false, true)
            : $this->request->get('updateSelect', false);

        //create the form
        $form = $model->createForm($entity, $this->get('form.factory'), $action, ['update_select' => $updateSelect]);

        ////Check for a submitted form and process it
        if ($method == 'POST') {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    $model->saveEntity($entity);

                    $this->addFlash(
                        'mautic.core.notice.created',
                        [
                            '%name%'        =>  $entity->getName(),
                            '%menu_link%'   =>  'mautic_sms_sign_index',
                            '%url%'         => $this->generateUrl(
                                'mautic_sms_sign_action',
                                [
                                    'objectAction'  =>  'edit',
                                    'objectId'      =>  $entity->getId(),
                                ]
                            ),
                        ]
                    );

                    if ($form->get('buttons')->get('save')->isClicked()) {
                        $viewParameters = ['page' => $page];
                        $returnUrl = $this->generateUrl('mautic_sms_sign_index', $viewParameters);
                        $template = 'MauticSmsBundle:Sign:index';
                    } else {
                        return $this->editAction($entity->getId(), true);
                    }
                }
            } else {
                $viewParameters = ['page' => $page];
                $returnUrl      = $this->generateUrl('mautic_sms_sign_index', $viewParameters);
                $template       = 'MauticSmsBundle:Sign:index';
                //clear any modified content
                $session->remove('mautic.sign.'.$entity->getId().'.content');
            }

            $passthrough = [
                'activeLink'    => 'mautic_sms_sign_index',
                'mauticContent' => 'sign',
            ];

            // Check to see if this is a popup
            if (isset($form['updateSelect'])) {
                $template    = false;
                $passthrough = array_merge(
                    $passthrough,
                    [
                        'updateSelect' => $form['updateSelect']->getData(),
                        'id'           => $entity->getId(),
                        'name'         => $entity->getName(),
                        'group'        => $entity->getLanguage(),
                    ]
                );
            }

            if ($cancelled || ($valid && $form->get('buttons')->get('save')->isClicked())) {
                return $this->postActionRedirect(
                    [
                        'returnUrl'       => $returnUrl,
                        'viewParameters'  => $viewParameters,
                        'contentTemplate' => $template,
                        'passthroughVars' => $passthrough,
                    ]
                );
            }
        }

        return $this->delegateView(
            [
                'viewParameters' => [
                    'form' => $this->setFormTheme($form, 'MauticSmsBundle:Sign:form.html.php', 'MauticSmsBundle:FormTheme\Sign'),
                    'sign'  => $entity,
                ],
                'contentTemplate' => 'MauticSmsBundle:Sign:form.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_sms_sign_index',
                    'mauticContent' => 'sms',
                    'updateSelect'  => InputHelper::clean($this->request->query->get('updateSelect')),
                    'route'         => $this->generateUrl(
                        'mautic_sms_sign_action',
                        [
                            'objectAction' => 'new',
                        ]
                    ),
                ],
            ]
        );
    }

    /**
     * @param      $objectId
     * @param bool $ignorePost
     * @param bool $forceTypeSelection
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction($objectId, $ignorePost = false, $forceTypeSelection = false)
    {
        /** @var \Mautic\SmsBundle\Model\SignModel $model */
        $model   = $this->getModel('sign');
        $method  = $this->request->getMethod();
        $entity  = $model->getEntity($objectId);
        $session = $this->get('session');
        $page    = $session->get('mautic.sign.page', 1);

        //set the return URL
        $returnUrl = $this->generateUrl('mautic_sms_sign_index', ['page' => $page]);

        $postActionVars = [
            'returnUrl'       => $returnUrl,
            'viewParameters'  => ['page' => $page],
            'contentTemplate' => 'MauticSmsBundle:Sign:index',
            'passthroughVars' => [
                'activeLink'    => 'mautic_sms_sign_index',
                'mauticContent' => 'sign',
            ],
        ];

        //not found
        if ($entity === null) {
            return $this->postActionRedirect(
                array_merge(
                    $postActionVars,
                    [
                        'flashes' => [
                            [
                                'type'    => 'error',
                                'msg'     => 'mautic.sms.error.notfound',
                                'msgVars' => ['%id%' => $objectId],
                            ],
                        ],
                    ]
                )
            );
        } elseif (!$this->get('mautic.security')->hasEntityAccess(
            'sms:smses:viewown',
            'sms:smses:viewother',
            $entity->getCreatedBy()
        )
        ) {
            return $this->accessDenied();
        } elseif ($model->isLocked($entity)) {
            //deny access if the entity is locked
            return $this->isLocked($postActionVars, $entity, 'sign');
        }

        //Create the form
        $action = $this->generateUrl('mautic_sms_sign_action', ['objectAction' => 'edit', 'objectId' => $objectId]);

        $updateSelect = ($method == 'POST')
            ? $this->request->request->get('sign[updateSelect]', false, true)
            : $this->request->get('updateSelect', false);

        $form = $model->createForm($entity, $this->get('form.factory'), $action, ['update_select' => $updateSelect]);

        ///Check for a submitted form and process it
        if (!$ignorePost && $method == 'POST') {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    //form is valid so process the data
                    $model->saveEntity($entity, $form->get('buttons')->get('save')->isClicked());

                    $this->addFlash(
                        'mautic.core.notice.updated',
                        [
                            '%name%'      => $entity->getName(),
                            '%menu_link%' => 'mautic_sms_sign_index',
                            '%url%'       => $this->generateUrl(
                                'mautic_sms_sign_action',
                                [
                                    'objectAction' => 'edit',
                                    'objectId'     => $entity->getId(),
                                ]
                            ),
                        ],
                        'warning'
                    );
                }
            } else {
                //clear any modified content
                $session->remove('mautic.sign.'.$objectId.'.content');
                //unlock the entity
                $model->unlockEntity($entity);
            }

            $passthrough = [
                'activeLink'    => 'mautic_sms_sign_index',
                'mauticContent' => 'sign',
            ];

            $template = 'MauticSmsBundle:Sign:index';

            // Check to see if this is a popup
            if (isset($form['updateSelect'])) {
                $template    = false;
                $passthrough = array_merge(
                    $passthrough,
                    [
                        'updateSelect' => $form['updateSelect']->getData(),
                        'id'           => $entity->getId(),
                        'name'         => $entity->getName(),
                        'group'        => $entity->getLanguage(),
                    ]
                );
            }

            if ($cancelled || ($valid && $form->get('buttons')->get('save')->isClicked())) {
                $viewParameters = ['page' => $page];

                return $this->postActionRedirect(
                    array_merge(
                        $postActionVars,
                        [
                            'returnUrl'       => $this->generateUrl('mautic_sms_sign_index', $viewParameters),
                            'viewParameters'  => $viewParameters,
                            'contentTemplate' => $template,
                            'passthroughVars' => $passthrough,
                        ]
                    )
                );
            }
        } else {
            //lock the entity
            $model->lockEntity($entity);
        }

        return $this->delegateView(
            [
                'viewParameters' => [
                    'form'               => $this->setFormTheme($form, 'MauticSmsBundle:Sign:form.html.php', 'MauticSmsBundle:FormTheme\Sign'),
                    'sign'                => $entity,
                    'forceTypeSelection' => $forceTypeSelection,
                ],
                'contentTemplate' => 'MauticSmsBundle:Sign:form.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_sms_sign_index',
                    'mauticContent' => 'sign',
                    'updateSelect'  => InputHelper::clean($this->request->query->get('updateSelect')),
                    'route'         => $this->generateUrl(
                        'mautic_sms_sign_action',
                        [
                            'objectAction' => 'edit',
                            'objectId'     => $entity->getId(),
                        ]
                    ),
                ],
            ]
        );
    }


}
