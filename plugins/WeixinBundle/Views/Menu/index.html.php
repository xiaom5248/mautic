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
$view['slots']->set('mauticContent', 'weixin');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.weixin.menu'));

$view['slots']->set(
    'actions',
    $view->render(
        'WeixinBundle:Common:switcher.html.php',
        [
            'currentWeixin' => $currentWeixin,
        ]
    )
);

$pageButtons = [];

?>
<div class="box-layout">
    <div class="col-md-12 bg-white height-auto">
        <div class="row">
            <div class="weixin-menu">
                <div class="weixin-menu-bar">

                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>