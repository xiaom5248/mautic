<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Entity;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class SmsRepository.
 */
class QrcodeRepository extends CommonRepository
{

    public function getNextIndex(Weixin $weixin)
    {
        $q = $this->createQueryBuilder('qr')
            ->select('MAX(qr.nb) AS nb')
            ->where('qr.weixin = '.$weixin->getId())
            ->groupBy('qr.weixin');

        $index = $q->getQuery()->getResult();

        return (isset($index[0]) && isset($index[0]['nb'])) ? $index[0]['nb'] + 1 : 1;
    }

    public function getCount()
    {
        $q = $this->createQueryBuilder('qr')
            ->select('count(qr.id) AS nb');
//            ->where('qr.weixin = '.$weixin->getId());


        return $q->getQuery()->getSingleScalarResult();
    }

}
