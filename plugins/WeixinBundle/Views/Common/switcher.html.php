<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>
<!--
some stats: need more input on what type of form data to show.
delete if it is not require
-->

<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_auto_res'); ?>">weixin.auto_res</a>
<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_menu'); ?>">weixin.menu</a>
<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_qrcode'); ?>">weixin.qrcode</a>
<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_article'); ?>">weixin.article</a>
<a><?php echo $currentWeixin->getAccountName(); ?></a>
<!--/ some stats -->
