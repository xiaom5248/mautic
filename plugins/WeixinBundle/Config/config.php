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
            'mautic_weixin_auto_res_followed_message' => [
                'path'         => '/weixin/auto-res/followed-message',
                'controller'   => 'WeixinBundle:AutoRes:followedMessage',
            ],
            'mautic_weixin_auto_res_keyword_message' => [
                'path'         => '/weixin/auto-res/keyword-message',
                'controller'   => 'WeixinBundle:AutoRes:keywordMessage',
            ],

            'mautic_weixin_menu' => [
                'path'         => '/weixin/menu',
                'controller'   => 'WeixinBundle:Menu:index',
            ],
            'mautic_weixin_qrcode' => [
                'path'         => '/weixin/qrcode',
                'controller'   => 'WeixinBundle:Qrcode:index',
            ],
            'mautic_weixin_article' => [
                'path'         => '/weixin/article',
                'controller'   => 'WeixinBundle:Article:index',
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
