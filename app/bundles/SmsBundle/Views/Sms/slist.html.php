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
                        'sessionVar' => 'sms',
                        'orderBy'    => 'e.name',
                        'text'       => '发送群组',
                        'class'      => 'col-sms-name',
                        'default'    => true,
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'sms',
                        'orderBy'    => 'e.message',
                        'text'       => '发送时间',
                        'class'      => 'col-sms-message',
                    ]
                );
                ?>

                <th class="visible-sm visible-md visible-lg col-sms-stats"><?php echo '状态'; ?></th>

                <th class="visible-sm visible-md visible-lg col-sms-stats"><?php echo $view['translator']->trans('mautic.core.stats'); ?></th>

                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'sms',
                        'orderBy'    => 'e.id',
                        'text'       => 'mautic.core.id',
                        'class'      => 'visible-md visible-lg col-sms-id',
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var \Mautic\SmsBundle\Entity\Event $item */
            foreach ($items as $item):

                ?>
                <tr>

                    <td>
                        <div>
                            <?php echo $item->getProperties()['segment_name']; ?>
                        </div>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php echo $view['date']->toText($item->getTriggerDate()); ?>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php echo $item->getStatus() == 1 ? "已发送" : "待发送"; ?>
                    </td>
                    <td class="visible-sm visible-md visible-lg col-stats">
                        <span class="mt-xs label label-warning"><?php echo $view['translator']->trans(
                                'mautic.sms.stat.sentcount',
                                ['%count%' => $item->getSendCount()]
                            ); ?></span>
                    </td>
                    <td class="visible-md visible-lg"><?php echo $item->getId(); ?></td>
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
