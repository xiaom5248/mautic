<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'sms');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.sms.smses'));

$pageButtons = [];
if($permissions['sms:smses:create']){
    $pageButtons[] = [
            'attr'  =>   [
                'class'       => 'btn btn-default btn-nospin quickadd',
                'href'        => $view['router']->path('mautic_sms_sign_index'),
                'data-header' => $view['translator']->trans('mautic.sms.signs.manage'),
            ],
            'iconClass' => 'fa fa-edit',
            'btnText'   => '签名管理',
            'primary'   => true,
    ];

    $pageButtons[] = [
        'attr'  =>   [
            'class'       => 'btn btn-default btn-nospin quickadd',
            'href'        => $view['router']->path('mautic_contact_action', ['objectAction' => 'quickAdd']),
            'data-header' => $view['translator']->trans('mautic.lead.lead.menu.quickadd'),
        ],
        'iconClass' => 'fa fa-comment',
        'btnText'   => '事务性短信',
        'primary'   => true,
    ];
}

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => $permissions['sms:smses:create'],
            ],
            'routeBase' => 'sms',
            'customButtons' => $pageButtons,
        ]
    )
);

?>

<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php echo $view->render(
        'MauticCoreBundle:Helper:list_toolbar.html.php',
        [
            'searchValue' => $searchValue,
            'searchHelp'  => 'mautic.sms.help.searchcommands',
            'searchId'    => 'sms-search',
            'action'      => $currentRoute,
            // 'filters'     => $filters // @todo
        ]
    ); ?>

    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>

