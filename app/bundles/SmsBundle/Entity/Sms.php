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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\LeadBundle\Entity\LeadList;
use Mautic\LeadBundle\Form\Validator\Constraints\LeadListAccess;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class Sms.
 */
class Sms extends FormEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $language = 'en';

    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTime
     */
    private $publishUp;

    /**
     * @var \DateTime
     */
    private $publishDown;

    /**
     * @var int
     */
    private $sentCount = 0;

    /**
     * @var int
     */
    private $groupSendCount = 0;

    /**
     * @var \Mautic\CategoryBundle\Entity\Category
     **/
    private $category;

    /**
     * @var \Mautic\SmsBundle\Entity\Sign
     */
    private $sign;

    /**
     * @var ArrayCollection
     */
    private $lists;

    /**
     * @var ArrayCollection
     */
    private $stats;

    /**
     * @var \Mautic\LeadBundle\Entity\LeadList
     */
    private $segment;

    /**
     * @var string
     */
    private $smsType = 'template';

    public function __clone()
    {
        $this->id        = null;
        $this->stats     = new ArrayCollection();
        $this->sentCount = 0;
        $this->readCount = 0;
        $this->groupSendCount = 0;

        parent::__clone();
    }

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->stats = new ArrayCollection();
    }

    /**
     * Clear stats.
     */
    public function clearStats()
    {
        $this->stats = new ArrayCollection();
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('sms_messages')
            ->setCustomRepositoryClass('Mautic\SmsBundle\Entity\SmsRepository');

        $builder->addIdColumns();

        $builder->createField('language', 'string')
            ->columnName('lang')
            ->build();

        $builder->createField('message', 'text')
            ->build();

        $builder->createField('smsType', 'text')
            ->columnName('sms_type')
            ->nullable()
            ->build();

        $builder->addPublishDates();

        $builder->createField('sentCount', 'integer')
            ->columnName('sent_count')
            ->build();

        $builder->createField('groupSendCount', 'integer')
            ->columnName('group_send_count')
            ->build();


        $builder->addCategory();

        $builder->createManyToMany('lists', 'Mautic\LeadBundle\Entity\LeadList')
            ->setJoinTable('sms_message_list_xref')
            ->setIndexBy('id')
            ->addInverseJoinColumn('leadlist_id', 'id', false, false, 'CASCADE')
            ->addJoinColumn('sms_id', 'id', false, false, 'CASCADE')
            ->fetchExtraLazy()
            ->build();

        $builder->createOneToMany('stats', 'Stat')
            ->setIndexBy('id')
            ->mappedBy('sms')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->createOneToMany('segment','Mautic\LeadBundle\Entity\LeadList')
            ->setIndexBy('id')
            ->mappedBy('sms')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->createManyToOne('sign','Mautic\SmsBundle\Entity\Sign')
            ->addJoinColumn('sign_id', 'id', true, false, 'SET NULL')
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

        $metadata->addConstraint(new Callback([
            'callback' => function (Sms $sms, ExecutionContextInterface $context) {
                $type = $sms->getSmsType();
                if ($type == 'list') {
                    $validator = $context->getValidator();
                    $violations = $validator->validate(
                        $sms->getLists(),
                        [
                            new LeadListAccess(
                                [
                                    'message' => 'mautic.lead.lists.required',
                                ]
                            ),
                            new NotBlank(
                                [
                                    'message' => 'mautic.lead.lists.required',
                                ]
                            ),
                        ]
                    );

                    if (count($violations) > 0) {
                        $string = (string) $violations;
                        $context->buildViolation($string)
                            ->atPath('lists')
                            ->addViolation();
                    }
                }
            },
        ]));
    }

    /**
     * Prepares the metadata for API usage.
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('sms')
            ->addListProperties(
                [
                    'id',
                    'name',
                    'message',
                    'language',
                    'category',
                    'sign'
                ]
            )
            ->addProperties(
                [
                    'publishUp',
                    'publishDown',
                    'sentCount',
                    'segment'
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

        if ($prop == 'category' || $prop == 'list' || $prop == 'sign') {
            $currentId = ($current) ? $current->getId() : '';
            $newId     = ($val) ? $val->getId() : null;
            if ($currentId != $newId) {
                $this->changes[$prop] = [$currentId, $newId];
            }
        }
        elseif($prop == 'segment'){
            if($current && !$val) {
                $this->changes['segment'] = [$current->getId(),$val];
            } elseif(!$current && $val) {
                $this->changes['segment'] = [$current, $val->getId()];
            } elseif($current && $val && $current->getId() != $val->getId()){
                $this->changes['segment'] = [$current->getId(), $val->getId()];
            }
        }
        else {
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->isChanged('description', $description);
        $this->description = $description;
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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->isChanged('category', $category);
        $this->category = $category;

        return $this;
    }

    /**
     * @return Sign
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param Sign $sign
     */
    public function setSign($sign)
    {
        $this->isChanged('sign', $sign);
        $this->sign = $sign;

        return $this;
    }



    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->isChanged('message', $message);
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->isChanged('language', $language);
        $this->language = $language;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishDown()
    {
        return $this->publishDown;
    }

    /**
     * @param $publishDown
     *
     * @return $this
     */
    public function setPublishDown($publishDown)
    {
        $this->isChanged('publishDown', $publishDown);
        $this->publishDown = $publishDown;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishUp()
    {
        return $this->publishUp;
    }

    /**
     * @param $publishUp
     *
     * @return $this
     */
    public function setPublishUp($publishUp)
    {
        $this->isChanged('publishUp', $publishUp);
        $this->publishUp = $publishUp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSentCount()
    {
        return $this->sentCount;
    }

    /**
     * @param $sentCount
     *
     * @return $this
     */
    public function setSentCount($sentCount)
    {
        $this->sentCount = $sentCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getGroupSendCount()
    {
        return $this->groupSendCount;
    }

    /**
     * @param int $groupSendCount
     */
    public function setGroupSendCount($groupSendCount)
    {
        $this->groupSendCount = $groupSendCount;
    }



    /**
     * @return LeadList
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * @param LeadList $segment
     */
    public function setSegment(LeadList $segment = null)
    {
        $this->isChanged('segment',$segment);
        $this->segment = $segment;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * Add list.
     *
     * @param LeadList $list
     *
     * @return Sms
     */
    public function addList(LeadList $list)
    {
        $this->lists[] = $list;

        return $this;
    }

    /**
     * Remove list.
     *
     * @param LeadList $list
     */
    public function removeList(LeadList $list)
    {
        $this->lists->removeElement($list);
    }

    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @return string
     */
    public function getSmsType()
    {
        return $this->smsType;
    }

    /**
     * @param string $smsType
     */
    public function setSmsType($smsType)
    {
        $this->isChanged('smsType', $smsType);
        $this->smsType = $smsType;
    }
}
