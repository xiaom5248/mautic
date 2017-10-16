<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
if ($tmpl == 'index') {
    $view->extend('MauticSmsBundle:Sms:index.html.php');
}

if (count($items)):

    ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered sms-list">
            <thead>
            <tr>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'checkall'        => 'true',
                        'routeBase'       => 'sms',
                        'templateButtons' => [
                            'delete' => $permissions['sms:smses:deleteown'] || $permissions['sms:smses:deleteother'],
                        ],
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'sms',
                        'orderBy'    => 'e.name',
                        'text'       => '短信名称',
                        'class'      => 'col-sms-name',
                        'default'    => true,
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'sms',
                        'orderBy'    => 'e.message',
                        'text'       => 'mautic.sms.text',
                        'class'      => 'col-sms-message',
                    ]
                );
                ?>

                <th class="visible-sm visible-md visible-lg col-sms-stats"><?php echo $view['translator']->trans('mautic.sms.timeline.type'); ?></th>

                <th class="visible-sm visible-md visible-lg col-sms-stats"><?php echo $view['translator']->trans('mautic.core.stats'); ?></th>

                <th class="visible-sm visible-md visible-lg col-sms-stats">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var \Mautic\SmsBundle\Entity\Sms $item */
            foreach ($items as $item):
                $type = $item->getSmsType();
                ?>
                <tr>
                    <td>
                        <?php
                        $edit = $view['security']->hasEntityAccess(
                            $permissions['sms:smses:editown'],
                            $permissions['sms:smses:editother'],
                            $item->getCreatedBy()
                        );
                        $customButtons = [
                            [
                                'attr' => [
                                    'data-toggle' => 'ajaxmodal',
                                    'data-target' => '#MauticSharedModal',
                                    'data-header' => $view['translator']->trans('mautic.sms.smses.header.preview'),
                                    'data-footer' => 'false',
                                    'href'        => $view['router']->path(
                                        'mautic_sms_action',
                                        ['objectId' => $item->getId(), 'objectAction' => 'preview']
                                    ),
                                ],
                                'btnText'   => $view['translator']->trans('mautic.sms.preview'),
                                'iconClass' => 'fa fa-share',
                            ],
                        ];
                        if($item->getSentCount(true)>0){
                            echo $view->render(
                                'MauticCoreBundle:Helper:list_actions.html.php',
                                [
                                    'item'            => $item,
                                    'templateButtons' => [
                                        'clone'  => $permissions['sms:smses:create'],
                                        'delete' => $view['security']->hasEntityAccess(
                                            $permissions['sms:smses:deleteown'],
                                            $permissions['sms:smses:deleteother'],
                                            $item->getCreatedBy()
                                        ),
                                    ],
                                    'routeBase'     => 'sms',
                                    'customButtons' => $customButtons,
                                ]
                            );
                        }else{
                            echo $view->render(
                                'MauticCoreBundle:Helper:list_actions.html.php',
                                [
                                    'item'            => $item,
                                    'templateButtons' => [
                                        'edit' => $view['security']->hasEntityAccess(
                                            $permissions['sms:smses:editown'],
                                            $permissions['sms:smses:editother'],
                                            $item->getCreatedBy()
                                        ),
                                        'clone'  => $permissions['sms:smses:create'],
                                        'delete' => $view['security']->hasEntityAccess(
                                            $permissions['sms:smses:deleteown'],
                                            $permissions['sms:smses:deleteother'],
                                            $item->getCreatedBy()
                                        ),
                                    ],
                                    'routeBase'     => 'sms',
                                    'customButtons' => $customButtons,
                                ]
                            );
                        }

                        ?>
                    </td>
                    <td>
                        <div>
                            <?php if ($type == 'template' || $type == 'sale'): ?>
                                <?php echo $view->render(
                                    'MauticCoreBundle:Helper:publishstatus_icon.html.php',
                                    ['item' => $item, 'model' => 'sms']
                                ); ?>
                            <?php else: ?>
                                <i class="fa fa-fw fa-lg fa-toggle-on text-muted disabled"></i>
                            <?php endif; ?>
                            <a href="<?php echo $view['router']->path(
                                'mautic_sms_action',
                                ['objectAction' => 'view', 'objectId' => $item->getId()]
                            ); ?>">
                                <?php echo $item->getName(); ?>
                                <?php if ($type == 'list'): ?>
                                    <span data-toggle="tooltip" title="<?php echo $view['translator']->trans('mautic.sms.icon_tooltip.list_sms'); ?>"><i class="fa fa-fw fa-list"></i></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php echo $item->getMessage(); ?>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php echo $item->getSmsType() == "template" ? "事务性短信" : "推广性短信"; ?>
                    </td>
                    <td class="visible-sm visible-md visible-lg col-stats">
                        <span class="mt-xs label label-warning">已群发<?php echo $item->getGroupSendCount(); ?>次</span>
                    </td>
                    <td class="visible-md visible-lg"><a href="<?php echo $view['router']->path(
                            'mautic_sms_slist',
                            ['objectId' => $item->getId()]
                        ); ?>"><span><i class="iconfont">&#xe65c;</i></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <?php echo $view->render(
            'MauticCoreBundle:Helper:pagination.html.php',
            [
                'totalItems' => $totalItems,
                'page'       => $page,
                'limit'      => $limit,
                'baseUrl'    => $view['router']->path('mautic_sms_index'),
                'sessionVar' => 'sms',
            ]
        ); ?>
    </div>
<?php elseif (!$configured): ?>
    <?php echo $view->render(
        'MauticCoreBundle:Helper:noresults.html.php',
        ['header' => 'mautic.sms.disabled', 'message' => 'mautic.sms.enable.in.configuration']
    ); ?>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php', ['message' => 'mautic.sms.create.in.campaign.builder']); ?>
<?php endif; ?>
