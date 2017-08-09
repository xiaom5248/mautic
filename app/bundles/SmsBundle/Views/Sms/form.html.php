
<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$view->extend('MauticCoreBundle:FormTheme:form_simple.html.php');
$view->addGlobal('translationBase', 'mautic.sms');
$view->addGlobal('mauticContent', 'sms');

?>

<?php $view['slots']->start('primaryFormContent'); ?>
<div class="row">
    <div class="col-md-6">
        <?php echo $view['form']->row($form['name']); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="counter" style="text-align: right;margin-bottom: -18px;"><span id="char">0</span>字符,<span id="piece">0</span>条</div>


        <div class="modal fade" id="shortenModal" tabindex="-1" role="dialog" aria-labelledby="shortenModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">插入短链接</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label class="control-label">注意:</label>短链接有效期为90天</div>
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="short-url" class="control-label">插入链接:</label>
                                <input type="text" class="form-control" id="short-url">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary short_insert">插入</button>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $view['form']->row($form['message']); ?>
    </div>
</div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start('rightFormContent'); ?>
<?php echo $view['form']->row($form['category']); ?>
<?php echo $view['form']->row($form['language']); ?>
<div class="hide">
    <?php echo $view['form']->row($form['isPublished']); ?>
    <?php echo $view['form']->row($form['publishUp']); ?>
    <?php echo $view['form']->row($form['publishDown']); ?>

    <?php echo $view['form']->rest($form); ?>
</div>
<?php $view['slots']->stop(); ?>

