<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-28
 * Time: 上午11:14
 */

namespace MauticPlugin\WeixinBundle\Enum;

class WeixinEnum
{
    static $types = [
        0 => '订阅号',
        1 => '订阅号',
        2 => '服务号'
    ];

    static $verified = [
        -1 => '未认证',
        0 => '微信认证',
        1 => '新浪微博认证',
        2 => '腾讯微博认证',
        3 => '已资质认证通过但还未通过名称认证',
        4 => '已资质认证通过、还未通过名称认证，但通过了新浪微博认证',
        5 => '已资质认证通过、还未通过名称认证，但通过了腾讯微博认证'
    ];
}