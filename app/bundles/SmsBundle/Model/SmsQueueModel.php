<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-09
 * Time: 10:34
 */

namespace Mautic\SmsBundle\Model;


use Mautic\CoreBundle\Model\FormModel;
use Mautic\CoreBundle\Helper\CoreParametersHelper;
use Mautic\LeadBundle\Model\ListModel;

class SmsQueueModel extends FormModel
{
    /**
     * @var ListModel
     */
    protected $listModel;


    /**
     * @var CoreParametersHelper
     */
    protected $coreParametersHelper;

    /**
     * SmsQueueModel constructor.
     * @param ListModel $listModel
     * @param CoreParametersHelper $coreParametersHelper
     */
    public function __construct(ListModel $listModel, CoreParametersHelper $coreParametersHelper)
    {
        $this->listModel                    =   $listModel;
        $this->coreParametersHelper         =   $coreParametersHelper;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Mautic\SmsBundle\Entity\SmsQueueRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('MauticSmsBundle:SmsQueue');
    }

    public function addToQueue(
        $lists
    )
    {

    }
}