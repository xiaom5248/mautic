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
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.weixin.auto_res'));

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
            <div class="col-xs-12">
                <ul class="bg-auto nav nav-tabs pr-md pl-md">
                    <li class="active">
                        <a href="#followed" role="tab" data-toggle="tab">
                            被添加自动回复 </a>
                    </li>
                    <li class="">
                        <a href="#keyword" role="tab" data-toggle="tab">
                            关键词自动回复 </a>
                    </li>
                </ul>

                <!-- start: tab-content -->
                <div class="tab-content pa-md">
                    <div class="tab-pane fade bdr-w-0 active in" id="followed">
                        <div class="row">
                            <div class="col-md-12">
                                <?php $view['form']->start($followedMessageForm); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $view['form']->row($followedMessageForm['followedMessage']); ?>
                                    </div>
                                </div>
                                <?php $view['form']->end($followedMessageForm); ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade bdr-w-0" id="keyword">
                        <div class="row">
                            <div class="col-md-12">
                                <?php $view['form']->start($keywordMessageForm); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $view['form']->label($keywordMessageForm['rules']); ?>
                                        <a data-prototype="<?php echo $view->escape($view['form']->row($keywordMessageForm['rules']->vars['prototype'])); ?>"
                                           class="btn btn-warning btn-xs btn-add-item" href="#"
                                           onclick="addWeixinRule(this)">
                                            <?php echo $view['translator']->trans('mautic.core.form.list.additem'); ?>
                                        </a>
                                        <?php echo $view['form']->widget($keywordMessageForm['rules']); ?>
                                    </div>
                                </div>
                                <?php $view['form']->end($keywordMessageForm); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addWeixinRule(btn) {
        var count = mQuery(btn).data('count');
        var prototype = mQuery(btn).attr('data-prototype');
        prototype = prototype.replace(/__name__/g, count);
        mQuery(prototype).appendTo(mQuery('#weixin_keyword_message_rules'));
        count++;
        mQuery(btn).data('count', count);
    }
</script>