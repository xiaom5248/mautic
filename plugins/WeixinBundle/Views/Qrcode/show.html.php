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
$view['slots']->set('headerTitle', $view['translator']->trans('weixin.qrcode'));

$view['slots']->set(
    'actions',
    $view->render(
        'WeixinBundle:Common:switcher.html.php',
        [
            'currentWeixin' => $currentWeixin, 'weixins' => $weixins,
        ]
    )
);

$pageButtons = [];

?>


<div class="col-md-12 bg-white height-auto">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed">
                <tr>
                    <th>二维码名称</th>
                    <td><?php echo $qrcode->getName() ?></td>
                </tr>
                <tr>
                    <th>公众号</th>
                    <td><?php echo $qrcode->getWeixin() ?></td>
                </tr>
                <tr>
                    <th>标签</th>
                    <td><?php echo $qrcode->getTag() ?></td>
                </tr>
                <tr>
                    <th>二维码</th>
                    <td><?php $qrCode = new \Endroid\QrCode\QrCode($qrcode->getUrl());
                        echo '<img style="max-height:150px;" src="' . $qrCode->writeDataUri() . '">' ?></td>
                <tr>
                <tr>
                    <th>访问人数</th>
                    <td><?php echo count($qrcode->getScans()) ?></td>
                <tr>
                <tr>
                    <th>关注人数</th>
                    <td><?php echo $qrcode->getSubNb() ?></td>
                <tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed">
                <tr>
                    <th>扫码时间</th>
                    <th>扫码地址</th>
                </tr>
                <?php foreach ($qrcode->getScans() as $scan): ?>
                    <tr>
                        <td><?php echo $scan->getProvince() . $scan->getCity() ?></td>
                        <td><?php echo $scan->getScanTime()->format('Y-m-d H:s:i') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>