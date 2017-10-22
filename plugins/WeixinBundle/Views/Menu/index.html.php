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

echo $view['assets']->includeStylesheet('plugins/WeixinBundle/Assets/css/menu.css');

?>
<div class="col-md-12 bg-white height-auto">
    <div class="row">
        <div class="col-md-5">
            <div class="weixin-window">
                <div class="weixin-menu-items" style="top: 295px; left: 16.0146px;">
                    <div class="weixin-menu-item">

                    </div>
                    <div class="weixin-menu-item">

                    </div>
                    <div class="weixin-menu-item">

                    </div>
                    <div class="weixin-menu-item">

                    </div>
                    <div class="weixin-menu-item">

                    </div>
                    <div class="arrow-down"></div>
                </div>
                <div class="weixin-menu">
                    <div class="weixin-menu-bar weixin-menu-selected col-md-4">

                    </div>
                    <div class="weixin-menu-bar col-md-4">

                    </div>
                    <div class="weixin-menu-bar col-md-4">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-success menu-editor">
                <div class="panel-heading">
                    <h4><i class="fa fa-trash delete-item cursor-pointer"></i></h4>
                </div>
                <div class="panel-body menu-editor-body">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    mQuery(document).ready(function () {
        mQuery('.weixin-menu-bar').on('click', function(){
            mQuery('.weixin-menu-bar, .weixin-menu-item').removeClass('weixin-menu-selected');
            mQuery(this).addClass('weixin-menu-selected');

            var offset = mQuery(this).offset();
            mQuery('.weixin-menu-items').offset({top: offset.top - 255, left :offset.left});
        });

        mQuery('.weixin-menu-item').on('click', function(){
            mQuery('.weixin-menu-item, .weixin-menu-bar').removeClass('weixin-menu-selected');
            mQuery(this).addClass('weixin-menu-selected');
        });
    });
</script>