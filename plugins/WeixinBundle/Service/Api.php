<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use MauticPlugin\WeixinBundle\Entity\Message;

class Api
{
    private $configs;

    public function __construct($configs)
    {
        $this->configs = $configs;
    }

    public function updateMenu()
    {

    }


}