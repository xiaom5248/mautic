<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-10
 * Time: 10:48
 */

namespace Mautic\SmsBundle\Entity;


use Mautic\CoreBundle\Entity\CommonRepository;

class EventRepository extends CommonRepository
{
//    public function getEntities(array $args = [])
//    {
//        $select = 'e';
//        $q      = $this
//            ->createQueryBuilder('e')
//            ->join('e.sms','s');
//
//        if(!empty($args['sms_id'])) {
//            $q->andWhere(
//                $q->expr()->eq('IDENTITY(e.sms)',(int) $args['sms_id'])
//            );
//        }
//
//        if(empty($args['ignore_children'])) {
//            $select .= ', ec, ep';
//            $q->leftJoin('e.children','ec')
//                ->leftJoin('e.parent','ep');
//        }
//
//        $q->select($select);
//
//        $args['qb'] = $q;
//
//        return parent::getEntities($args);
//    }
}