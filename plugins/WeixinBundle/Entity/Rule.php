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
 * Class Weixin.
 */
class Rule
{

    const RULE_TYPE_COMPLET = 'complet';
    const RULE_TYPE_LIKE = 'like';

    static $ruleTypes = [
        self::RULE_TYPE_COMPLET => 'weixin.rule.type.complet',
        self::RULE_TYPE_LIKE => 'weixin.rule.type.like',
    ];

    /**
     * @var int
     */
    private $id;

    private $name;

    private $message;

    private $keywords;

    private $type;

    private $weixin;

    public function __construct()
    {

    }

    public function getTypeText()
    {
        return Rule::$ruleTypes[$this->getType()];
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_rule')
            ->setCustomRepositoryClass('MauticPlugin\WeixinBundle\Entity\RuleRepository');

        $builder->addId();

        $builder->createField('type', 'string')
            ->columnName('type')
            ->build();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createOneToMany('keywords', 'Keyword')
            ->addJoinColumn('message_id', 'id', false, false, 'CASCADE')
            ->setIndexBy('id')
            ->mappedBy('rule')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->createManyToOne('message', 'Message')
            ->addJoinColumn('message_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('weixin','Weixin')
            ->addJoinColumn('weixin_id', 'id', false, false, 'CASCADE')
            ->build();

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getWeixin()
    {
        return $this->weixin;
    }

    /**
     * @param mixed $weixin
     */
    public function setWeixin($weixin)
    {
        $this->weixin = $weixin;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}
