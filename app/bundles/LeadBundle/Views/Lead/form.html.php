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
$header = ($lead->getId()) ?
    $view['translator']->trans('mautic.lead.lead.header.edit',
        ['%name%' => $view['translator']->trans($lead->getPrimaryIdentifier())]) :
    $view['translator']->trans('mautic.lead.lead.header.new');
$view['slots']->set('headerTitle', $header);
$view['slots']->set('mauticContent', 'lead');

$groups = array_keys($fields);
sort($groups);

$img = $view['lead_avatar']->getAvatar($lead);
?>
<?php echo $view['form']->start($form); ?>
<!-- start: box layout -->
<div class="box-layout">
    <!-- step container -->
<!--    <div class="col-md-3 bg-white height-auto">-->
<!--        <div class="pr-lg pl-lg pt-md pb-md">-->
<!--            <div class="media">-->
<!--                <div class="media-body">-->
<!--                    <img class="img-rounded img-bordered img-responsive media-object" src="--><?php //echo $img; ?><!--" alt="">-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="row mt-xs">-->
<!--                <div class="col-sm-12">-->
<!--                    --><?php //echo $view['form']->label($form['preferred_profile_image']); ?>
<!--                    --><?php //echo $view['form']->widget($form['preferred_profile_image']); ?>
<!--                </div>-->
<!--                <div-->
<!--                    class="col-sm-12--><?php //if ($view['form']->containsErrors($form['custom_avatar'])) {
//    echo ' has-error';
//} ?><!--"-->
<!--                    id="customAvatarContainer"-->
<!--                    style="--><?php //if ($form['preferred_profile_image']->vars['data'] != 'custom') {
//    echo 'display: none;';
//} ?><!--">-->
<!--                    --><?php //echo $view['form']->widget($form['custom_avatar']); ?>
<!--                    --><?php //echo $view['form']->errors($form['custom_avatar']); ?>
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <hr/>-->
<!---->
<!--            <ul class="list-group list-group-tabs">-->
<!--                --><?php //$step = 1; ?>
<!--                --><?php //foreach ($groups as $g): ?>
<!--                    --><?php //if (!empty($fields[$g])): ?>
<!--                        <li class="list-group-item --><?php //if ($step === 1) {
//    echo 'active';
//} ?><!--">-->
<!--                            <a href="#--><?php //echo $g; ?><!--" class="steps" data-toggle="tab">-->
<!--                                --><?php //echo $view['translator']->trans('mautic.lead.field.group.'.$g); ?>
<!--                            </a>-->
<!--                        </li>-->
<!--                        --><?php //++$step; ?>
<!--                    --><?php //endif; ?>
<!--                --><?php //endforeach; ?>
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
    <!--/ step container -->

    <!-- container -->
    <div class="row">
        <div class="col-md-9 bg-auto height-auto bdr-l">
            <div class="tab-content">
                <!-- pane -->
                <?php
                foreach ($groups as $key => $group):
                    if (isset($fields[$group])):
                        $groupFields = $fields[$group];
                        if (!empty($groupFields)): ?>
                            <div class="tab-pane fade<?php if ($key === 0): echo ' in active'; endif; ?> bdr-rds-0 bdr-w-0"
                                 id="<?php echo $group; ?>">
    <!--                            <div class="pa-md bg-auto bg-light-xs bdr-b">-->
    <!--                                <h4 class="fw-sb">--><?php //echo $view['translator']->trans('mautic.lead.field.group.'.$group); ?><!--</h4>-->
    <!--                            </div>-->

                                    <div class="form-group mb-0">
                                        <div class="row">
                                            <?php foreach ($groupFields as $alias => $field): ?>
                                                <div class="col-sm-6">
                                                    <?php if ($alias == 'company'): ?>
                                                        <?php
                                                            echo $view['form']->row($form['companies']);
                                                            $form[$alias]->setRendered();
                                                        ?>
                                                    <?php else: ?>
                                                        <?php echo $view['form']->row($form[$alias]); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="col-sm-6">
                                                <?php echo $view['form']->row($form['stage']); ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <?php echo $view['form']->row($form['tags']); ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <?php echo $view['form']->row($form['owner']); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php
                        endif;
                    endif;
                endforeach;
                ?>
                <!--/ #pane -->
            </div>
        </div>
    </div>
    <!--/ end: container -->
</div>
<?php echo $view['form']->end($form); ?>
<!--/ end: box layout -->
