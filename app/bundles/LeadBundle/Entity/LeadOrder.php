<?php

namespace Mautic\LeadBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class LeadOrder
{
    /**
     * @var int
     */
    private $id;

    private $origin;

    private $orderNo;

    private $orderTime;

    private $totalFee;

    private $orderLines;

    private $contact;


    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('lead_order');

        $builder->addId();

        $builder->createField('origin', 'string')
            ->columnName('origin')
            ->build();

        $builder->createField('orderNo', 'string')
            ->columnName('order_no')
            ->build();

        $builder->createField('orderTime', 'datetime')
            ->columnName('order_time')
            ->build();

        $builder->createField('totalFee', 'integer')
            ->columnName('total_fee')
            ->build();

        $builder->createOneToMany('orderLines', 'LeadOrderLine')
            ->setIndexBy('id')
            ->mappedBy('leadOrder')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->addContact();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @return mixed
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * @param mixed $orderTime
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
    }

    /**
     * @return mixed
     */
    public function getTotalFee()
    {
        return $this->totalFee;
    }

    /**
     * @param mixed $totalFee
     */
    public function setTotalFee($totalFee)
    {
        $this->totalFee = $totalFee;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderLines()
    {
        return $this->orderLines;
    }

    /**
     * @param ArrayCollection $orderLines
     */
    public function setOrderLines($orderLines)
    {
        $this->orderLines = $orderLines;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }


}