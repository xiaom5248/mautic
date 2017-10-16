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
    $view->extend('MauticSmsBundle:Sign:index.html.php');
}
?>

<?php if (count($items)): ?>
    <div class="table-responsive page-list">
        <table class="table table-hover table-striped table-bordered stage-list" id="signTable">
            <thead>
            <tr>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'checkall'        => 'true',
                        'target'          => '#signTable',
                        'routeBase'       => 'sms_sign',
                        'templateButtons' => [
                            'delete' => $permissions['sms:smses:deleteown'],
                        ],
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'sign',
                        'orderBy'    => 's.name',
                        'text'       => 'mautic.core.name',
                        'class'      => 'col-stage-name',
                        'default'    => true,
                    ]
                );


                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'sign',
                        'orderBy'    => 's.id',
                        'text'       => 'mautic.core.id',
                        'class'      => 'visible-md visible-lg col-stage-id',
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php
                        echo $view->render(
                            'MauticCoreBundle:Helper:list_actions.html.php',
                            [
                                'item'            => $item,
                                'templateButtons' => [
                                    'edit'   => $permissions['sms:smses:editown'],
                                    'clone'  => $permissions['sms:smses:create'],
                                    'delete' => $permissions['sms:smses:deleteown'],
                                ],
                                'routeBase' => 'sms_sign',
                            ]
                        );
                        ?>
                    </td>
                    <td>
                        <div>


                            <a href="<?php echo $view['router']->generate(
                                'mautic_sms_sign_action',
                                ['objectAction' => 'edit', 'objectId' => $item->getId()]
                            ); ?>" data-toggle="ajax">
                                <?php echo $item->getName(); ?>
                            </a>
                        </div>
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
                'totalItems' => count($items),
                'page'       => $page,
                'limit'      => $limit,
                'menuLinkId' => 'mautic_sms_sign_index',
                'baseUrl'    => $view['router']->generate('mautic_sms_sign_index'),
                'sessionVar' => 'stage',
            ]
        ); ?>
    </div>
<?php else: ?>
    <?php echo $view->render(
        'MauticCoreBundle:Helper:noresults.html.php',
        ['tip' => 'mautic.sms.action.noresults.tip']
    ); ?>
<?php endif; ?>
