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

<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_auto_res'); ?>"><?php echo $view['translator']->trans('weixin.auto_res'); ?></a>
<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_menu'); ?>"><?php echo $view['translator']->trans('weixin.menu'); ?></a>
<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_qrcode'); ?>"><?php echo $view['translator']->trans('weixin.qrcode'); ?></a>
<a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_article'); ?>"><?php echo $view['translator']->trans('weixin.article'); ?></a>
<a><?php echo $currentWeixin->getAccountName(); ?></a>
<!--/ some stats -->
