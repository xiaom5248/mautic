<?php

?>

<div class="panel">
    <div class="panel-body">

        <?php echo $view['form']->row($form['type']); ?>
        <?php echo $view['form']->row($form['keywords']); ?>
        <div class="panel">
            <div class="panel-body">
                <?php echo $view['form']->widget($form['message']); ?>
            </div>
        </div>

        <a class="btn btn-danger delete-rule">weixin.rule.delete</a>
    </div>
</div>
