<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-14
 * Time: 14:46
 */

namespace Mautic\SmsBundle\Model;


use Mautic\CoreBundle\Model\FormModel;
use Mautic\SmsBundle\Entity\Sign;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class SignModel extends FormModel
{

    public function __construct()
    {
    }

    /**
     * @return \Mautic\SmsBundle\Entity\SignRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository("MauticSmsBundle:Sign");
    }

    /**
     *
     */
    public function getPermissionBase()
    {
        return 'sms:smses';
    }

    public function saveEntities($entities, $unlock = true)
    {
        $batchSize = 20;
        foreach ($entities as $k => $entity) {
            $isNew = ($entity->getId()) ? false : true;

            $this->setTimestamps($entity,$isNew,$unlock);

            if($dispatchEvent = $entity instanceof Sign) {
                $event = $this->dispatchEvent('pre_save',$entity,$isNew);
            }

            $this->getRepository()->saveEntity($entity,false);

            if($dispatchEvent) {
                $this->dispatchEvent('post_save',$entity,$isNew,$event);
            }

            if((($k + 1) % $batchSize) === 0) {
                $this->em->flush();
            }
        }
        $this->em->flush();
    }

    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof Sign) {
            throw new MethodNotAllowedHttpException(['Sign']);
        }
        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create('sign', $entity, $options);
    }


    /**
     * @param null $id
     * @return Sign|null|object
     */
    public function getEntity($id = null)
    {
        if($id == null){
            $entity = new Sign();
        }else {
            $entity = parent::getEntity($id);
        }

        return $entity;
    }


}