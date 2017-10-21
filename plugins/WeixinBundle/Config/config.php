<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [

    'name'        => 'Weixin',
    'description' => 'Weixin bundle',
    'author'      => 'Meng Xiao',
    'version'     => '0.1.0',
    'routes' => [
        'main' => [
            'mautic_weixin_auto_res' => [
                'path'         => '/weixin/auto-res',
                'controller'   => 'WeixinBundle:AutoRes:index',
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.channels.weixin' => [
                    'route'  => 'mautic_weixin_auto_res',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent' => 'mautic.core.channels',
//                    'checks' => [
//                        'integration' => [
//                            'Twilio' => [
//                                'enabled' => true,
//                            ],
//                        ],
//                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'services' => [

    ],
];
