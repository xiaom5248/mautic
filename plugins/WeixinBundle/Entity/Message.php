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
class Message
{

    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_IMG = 'img';
    const MSG_TYPE_IMGTEXT = 'imgtext';

    static $msgTypes = [
        self::MSG_TYPE_TEXT => 'weixin.message.type.text',
        self::MSG_TYPE_IMG => 'weixin.message.type.img',
        self::MSG_TYPE_IMGTEXT => 'weixin.message.type.imgtext' ,
    ];

    /**
     * @var int
     */
    private $id;

    private $msgType;

    private $formUserName;

    private $toUserName;

    private $createTime;

    private $content;

    private $mediaId;

    private $articleTitle;

    private $articleDesc;

    private $articlePic;

    private $articleUrl;


    public function __construct()
    {
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_messages')
            ->setCustomRepositoryClass('MauticPlugin\WeixinBundle\Entity\WeixinRepository');

        $builder->addId();

        $builder->createField('msgType', 'string')
            ->columnName('msg_type')
            ->build();

        $builder->createField('formUserName', 'string')
            ->columnName('form_user_name')
            ->build();

        $builder->createField('toUserName', 'string')
            ->columnName('to_user_name')
            ->build();

        $builder->createField('createTime', 'datetime')
            ->columnName('create_time')
            ->build();

        $builder->createField('content', 'text')
            ->columnName('content')
            ->build();

        $builder->createField('mediaId', 'string')
            ->columnName('media_id')
            ->build();

        $builder->createField('articleTitle', 'string')
            ->columnName('article_title')
            ->build();

        $builder->createField('articleDesc', 'string')
            ->columnName('article_desc')
            ->build();

        $builder->createField('articlePic', 'string')
            ->columnName('article_pic')
            ->build();

        $builder->createField('articleUrl', 'string')
            ->columnName('article_url')
            ->build();
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
//        $metadata->addPropertyConstraint(
//            'name',
//            new NotBlank(
//                [
//                    'message' => 'mautic.core.name.required',
//                ]
//            )
//        );
//
//        $metadata->addConstraint(new Callback([
//            'callback' => function (Weixin $sms, ExecutionContextInterface $context) {
//                $type = $sms->getSmsType();
//                if ($type == 'list') {
//                    $validator = $context->getValidator();
//                    $violations = $validator->validate(
//                        $sms->getLists(),
//                        [
//                            new LeadListAccess(
//                                [
//                                    'message' => 'mautic.lead.lists.required',
//                                ]
//                            ),
//                            new NotBlank(
//                                [
//                                    'message' => 'mautic.lead.lists.required',
//                                ]
//                            ),
//                        ]
//                    );
//
//                    if (count($violations) > 0) {
//                        $string = (string) $violations;
//                        $context->buildViolation($string)
//                            ->atPath('lists')
//                            ->addViolation();
//                    }
//                }
//            },
//        ]));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMsgType()
    {
        return $this->msgType;
    }

    /**
     * @param mixed $msgType
     */
    public function setMsgType($msgType)
    {
        $this->msgType = $msgType;
    }

    /**
     * @return mixed
     */
    public function getFormUserName()
    {
        return $this->formUserName;
    }

    /**
     * @param mixed $formUserName
     */
    public function setFormUserName($formUserName)
    {
        $this->formUserName = $formUserName;
    }

    /**
     * @return mixed
     */
    public function getToUserName()
    {
        return $this->toUserName;
    }

    /**
     * @param mixed $toUserName
     */
    public function setToUserName($toUserName)
    {
        $this->toUserName = $toUserName;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * @param mixed $mediaId
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    }

    /**
     * @return mixed
     */
    public function getArticleTitle()
    {
        return $this->articleTitle;
    }

    /**
     * @param mixed $articleTitle
     */
    public function setArticleTitle($articleTitle)
    {
        $this->articleTitle = $articleTitle;
    }

    /**
     * @return mixed
     */
    public function getArticleDesc()
    {
        return $this->articleDesc;
    }

    /**
     * @param mixed $articleDesc
     */
    public function setArticleDesc($articleDesc)
    {
        $this->articleDesc = $articleDesc;
    }

    /**
     * @return mixed
     */
    public function getArticlePic()
    {
        return $this->articlePic;
    }

    /**
     * @param mixed $articlePic
     */
    public function setArticlePic($articlePic)
    {
        $this->articlePic = $articlePic;
    }

    /**
     * @return mixed
     */
    public function getArticleUrl()
    {
        return $this->articleUrl;
    }

    /**
     * @param mixed $articleUrl
     */
    public function setArticleUrl($articleUrl)
    {
        $this->articleUrl = $articleUrl;
    }



}