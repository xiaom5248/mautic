<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-14
 * Time: 15:13
 */

namespace Mautic\SmsBundle\Integration;


use Mautic\PluginBundle\Integration\AbstractIntegration;

class EmayIntegration extends AbstractIntegration
{
    /**
     * @var bool
     */
    protected $coreIntegration = true;

    /**
     * @return string
     */
    public function getName()
    {
        return "Emay";
    }

    public function getIcon()
    {
        return 'app/bundles/SmsBundle/Assets/img/Emay.jpg';
    }

    public function getSecretKeys()
    {
        return ['password'];
    }

    public function getRequiredKeyFields()
    {
        return [
            'username'  =>  'mautic.sms.config.form.sms.username',
            'password'  =>  'mautic.sms.config.form.sms.password',
        ];
    }

    public function getFormSettings()
    {
        return [
            'requires_callback'         =>  false,
            'requires_authorization'    =>  false,
        ];
    }

    public function getAuthenticationType()
    {
        return 'none';
    }


}