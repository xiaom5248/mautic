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
            'mautic_weixin_auto_res_new_rule' => [
                'path'         => '/weixin/auto-res/rule/new',
                'controller'   => 'WeixinBundle:AutoRes:newRule',
            ],
            'mautic_weixin_auto_res_edit_rule' => [
                'path'         => '/weixin/auto-res/rule/{id}/edit',
                'controller'   => 'WeixinBundle:AutoRes:editRule',
            ],
            'mautic_weixin_auto_res_edit_keyword' => [
                'path'         => '/weixin/auto-res/keyword/{id}/edit',
                'controller'   => 'WeixinBundle:AutoRes:editKeyword',
            ],
            'mautic_weixin_auto_res_delete_keyword' => [
                'path'         => '/weixin/auto-res/keyword/{id}/delete',
                'controller'   => 'WeixinBundle:AutoRes:deleteKeyword',
            ],
            'mautic_weixin_auto_res_delete_rule' => [
                'path'         => '/weixin/auto-res/rule/{id}/delete',
                'controller'   => 'WeixinBundle:AutoRes:deleteRule',
            ],
            'mautic_weixin_menu' => [
                'path'         => '/weixin/menu',
                'controller'   => 'WeixinBundle:Menu:index',
            ],
            'mautic_weixin_menu_edit_menu' => [
                'path'         => '/weixin/menu/edit/{id}',
                'controller'   => 'WeixinBundle:Menu:editMenu',
            ],
            'mautic_weixin_menu_edit_menu_item' => [
                'path'         => '/weixin/menu/edit/{menuId}/item/{id}',
                'controller'   => 'WeixinBundle:Menu:editMenuItem',
            ],
            'mautic_weixin_menu_delete_menu' => [
                'path'         => '/weixin/menu/delete/{id}',
                'controller'   => 'WeixinBundle:Menu:deleteMenu',
            ],
            'mautic_weixin_menu_delete_menu_item' => [
                'path'         => '/weixin/menu/delete/item/{id}',
                'controller'   => 'WeixinBundle:Menu:deleteMenuItem',
            ],
            'mautic_weixin_qrcode' => [
                'path'         => '/weixin/qrcode',
                'controller'   => 'WeixinBundle:Qrcode:index',
            ],
            'mautic_weixin_article' => [
                'path'         => '/weixin/article',
                'controller'   => 'WeixinBundle:Article:index',
            ],

            'mautic_weixin_open_oauth' => [
                'path'         => '/weixin-oauth',
                'controller'   => 'WeixinBundle:Open:oauthLogin',
            ],

        ],
        'public' => [
            'mautic_weixin_open_auth' => [
                'path'         => '/weixin/auth',
                'controller'   => 'WeixinBundle:Open:auth',
            ],
        ]
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
        'others' => [
            'weixin.helper.message' => [
                'class'     => 'MauticPlugin\WeixinBundle\Service\MessageHelper',
                'arguments' => ['%kernel.root_dir%']
            ],
            'weixin.open_application' => [
                'class'     => 'MauticPlugin\WeixinBundle\Service\Application',
                'arguments' => ['%mautic.weixin.open_configs%']
            ],
        ],
    ],
    'parameters' => [
        'weixin.open_configs' => [
            'debug'                => false,                        //是否调试模式
            'component_app_id'     => 'wx8c1a03d2f7e747c8',           //第三方公众平台app id
            'component_app_secret' => 'd12409c5c671eb649d9da1187b0e39db',       //第三方公众平台app secret
            'token'                => '123123',                      //公众号消息校验Token
            'aes_key'              => '1234567891234567891234567891234567891234567',                    //公众号消息加解密Key

            'redirect_uri' => 'http://m-demo.linkall.sh.cn/s/oauth-return',                  //授权回调页面URI
            'log' => [                                              //日志
                'level' => 'debug',
                'file'  => '/tmp/easyopenwechat.log',
            ],
        ]
    ]
];
