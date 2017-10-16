<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
/** @var \Mautic\SmsBundle\Entity\Sms $sms */

?>
<blockquote>
    <p>【<?=$sms->getSign()->getName()?>】<?=$sms->getMessage()?></p>
</blockquote>

<?php
echo $view['form']->form($form);
?>

<!--<form method="post" action="--><?php //echo $view['router']->path('mautic_sms_action', [
//    'objectAction' => 'send',
//    'objectId'     => $sms->getId(),
//]);  ?><!--" id="MauticSharedModal" role="dialog" aria-labelledby="MauticSharedModal-label" aria-hidden="false" style="display:block">-->
<!---->
<!--    <div id="sms">-->
<!--        <div class="row">-->
<!--            <div class="form-group col-xs-12">-->
<!--                <label class="control-label" for="segment">选择一个群组</label>-->
<!--                <div class="choice-wrapper">-->
<!--                    <select id="segment" name="segment" class="form-control">-->
<!--                        --><?php //foreach ($lists as $list): ?>
<!--                            <option value="--><?php //echo $list['id']; ?><!--">--><?php //echo $list['name']; ?><!--</option>-->
<!--                        --><?php //endforeach;?>
<!--                    </select>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="form-group col-xs-12"><input class="btn btn-default" type="submit" value="发送" /></div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--</form>-->






