<?php

if (!empty($form->vars['value'])) {
    echo '<img style="max-height: 450px;" src="' . $view['assets']->getUrl($form->vars['value']). '"/>';
}
