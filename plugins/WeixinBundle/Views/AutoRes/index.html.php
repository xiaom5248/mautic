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
                    <li class="<?php if ($module == 'followed') echo 'active in'; ?>">
                        <a href="#followed" role="tab" data-toggle="tab">
                            被添加自动回复 </a>
                    </li>
                    <li class="<?php if ($module == 'keyword') echo 'active in'; ?>">
                        <a href="#keyword" role="tab" data-toggle="tab">
                            关键词自动回复 </a>
                    </li>
                </ul>

                <!-- start: tab-content -->
                <div class="tab-content pa-md">
                    <div class="tab-pane fade bdr-w-0 <?php if ($module == 'followed') echo 'active in'; ?>" id="followed">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $view['form']->start($followedMessageForm); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $view['form']->row($followedMessageForm['followedMessage']); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $view['form']->row($followedMessageForm['save']); ?>
                                    </div>
                                </div>
                                <?php echo $view['form']->end($followedMessageForm); ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade bdr-w-0 <?php if ($module == 'keyword') echo 'active in'; ?>" id="keyword">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $view['form']->start($keywordMessageForm); ?>
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php echo $view['form']->row($followedMessageForm['save']); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php echo $view['form']->end($keywordMessageForm); ?>
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

    (function () {
        mQuery(document).ready(function() {
            mQuery('#weixin_followed_message_followedMessage_msgType').on('change', function() {
                var container = mQuery('#weixin_followed_message_followedMessage');
                container.find('[data-toggle="msg-type"]').closest('.row').hide();
                container.find('[toggle-type~="'+mQuery(this).val()+'"]').closest('.row').show();

            }).trigger('change');
        });
    })();

</script>