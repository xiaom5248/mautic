<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Mautic\UserBundle\Entity\User;

/**
 * Class Sms.
 */
class Sign extends FormEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    private $description;


    /**
     * @var int
     */
    private $stats;

    /**
     * @var \Mautic\UserBundle\Entity\User
     */
    private $owner;

    /**
     * @var \DateTime
     */
    private $applyAt;



    public function __clone()
    {
        $this->id        = null;
        $this->stats     = 0;

        parent::__clone();
    }

    public function __construct()
    {
    }



    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('sms_signs')
            ->setCustomRepositoryClass('Mautic\SmsBundle\Entity\SignRepository');

        $builder->addIdColumns();

        $builder->createManyToOne('owner','Mautic\UserBundle\Entity\User')
            ->cascadeDetach()
            ->cascadeMerge()
            ->addJoinColumn('owner_id','id',true,false,'SET NULL')
            ->build();

        $builder->createField('applyAt', 'datetime')
            ->columnName('apply_at')
            ->nullable()
            ->build();



        $builder->createField('stats', 'integer')
            ->columnName('sign_stat')
            ->nullable()
            ->build();
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint(
            'name',
            new NotBlank(
                [
                    'message' => 'mautic.core.name.required',
                ]
            )
        );
    }

    /**
     * Prepares the metadata for API usage.
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('sign')
            ->addListProperties(
                [
                    'id',
                    'name',
                    'stats',
                    'owner',
                    'applyAt',
                ]
            )
            ->addProperties(
                [

                ]
            )
            ->build();
    }

    /**
     * @param $prop
     * @param $val
     */
    protected function isChanged($prop, $val)
    {
        $getter  = 'get'.ucfirst($prop);
        $current = $this->$getter();

        if ($prop == 'owner') {
            if ($current && !$val) {
                $this->changes['owner'] = [$current->getId(), $val];
            } elseif (!$current && $val) {
                $this->changes['owner'] = [$current, $val->getId()];
            } elseif ($current && $val && $current->getId() != $val->getId()) {
                $this->changes['owner'] = [$current->getId(), $val->getId()];
            }
        } else {
            parent::isChanged($prop, $val);
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->isChanged('name', $name);
        $this->name = $name;

        return $this;
    }

    /**
     * Set owner.
     *
     * @param User $owner
     *
     * @return $this
     */
    public function setOwner(User $owner = null)
    {
        $this->isChanged('owner', $owner);
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getApplyAt()
    {
        return $this->applyAt;
    }

    /**
     * @param $applyAt
     *
     * @return $this
     */
    public function setApplyAt($applyAt)
    {
        $this->isChanged('applyAt', $applyAt);
        $this->applyAt = $applyAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->isChanged("description",$description);
        $this->description = $description;

    }



}
