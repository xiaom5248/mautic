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
class Weixin
{
    /**
     * @var int
     */
    private $id;

    private $accountName;

    private $scope;

    private $authorizerAppId;

    private $authorizerAccessToken;

    private $authorizerRefreshToken;

    private $followedMessage;

    private $owner;

    private $rules;

    private $menus;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin')
            ->setCustomRepositoryClass('MauticPlugin\WeixinBundle\Entity\WeixinRepository');

        $builder->addId();

        $builder->createField('accountName', 'string')
            ->columnName('account_name')
            ->build();

        $builder->createField('scope', 'string')
            ->columnName('scope')
            ->build();

        $builder->createField('authorizerAppId', 'string')
            ->columnName('authorizer_app_id')
            ->build();

        $builder->createField('authorizerAccessToken', 'string')
            ->columnName('authorizer_access_token')
            ->build();

        $builder->createField('authorizerRefreshToken', 'string')
            ->columnName('authorizer_refresh_token')
            ->build();

        $builder->createOneToMany('rules', 'Rule')
            ->setIndexBy('id')
            ->mappedBy('weixin')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->createOneToMany('menus', 'Menu')
            ->setIndexBy('id')
            ->mappedBy('weixin')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->createManyToOne('followedMessage','Message')
            ->addJoinColumn('followed_message_id', 'id', true, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('owner','Mautic\UserBundle\Entity\User')
            ->addJoinColumn('owner_id', 'id', false, false, 'CASCADE')
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
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * @param mixed $accountName
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return mixed
     */
    public function getAuthorizerAppId()
    {
        return $this->authorizerAppId;
    }

    /**
     * @param mixed $authorizerAppId
     */
    public function setAuthorizerAppId($authorizerAppId)
    {
        $this->authorizerAppId = $authorizerAppId;
    }

    /**
     * @return mixed
     */
    public function getAuthorizerAccessToken()
    {
        return $this->authorizerAccessToken;
    }

    /**
     * @param mixed $authorizerAccessToken
     */
    public function setAuthorizerAccessToken($authorizerAccessToken)
    {
        $this->authorizerAccessToken = $authorizerAccessToken;
    }

    /**
     * @return mixed
     */
    public function getAuthorizerRefreshToken()
    {
        return $this->authorizerRefreshToken;
    }

    /**
     * @param mixed $authorizerRefreshToken
     */
    public function setAuthorizerRefreshToken($authorizerRefreshToken)
    {
        $this->authorizerRefreshToken = $authorizerRefreshToken;
    }

    /**
     * @return mixed
     */
    public function getFollowedMessage()
    {
        return $this->followedMessage;
    }

    /**
     * @param mixed $followedMessage
     */
    public function setFollowedMessage($followedMessage)
    {
        $this->followedMessage = $followedMessage;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return ArrayCollection
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function addRule($rule)
    {
        $this->rules->add($rule);
    }

    public function removeRules($rule)
    {
        $this->rules->remove($rule);
    }

    /**
     * @param ArrayCollection $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return mixed
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param mixed $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
    }

    public function addMenu($menu)
    {
        $this->menus->add($menu);
    }

    public function removeMenu($menu)
    {
        $this->menus->remove($menu);
    }

}
