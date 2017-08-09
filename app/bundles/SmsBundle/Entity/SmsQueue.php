<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-08
 * Time: 15:17
 */

namespace Mautic\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;


class SmsQueue
{
    const STATUS_RESCHEDULE = 'rescheduled';
    const STATUS_PENDING    = 'pending';
    const STATUS_SENT       = 'sent';

    const PRIORITY_NORMAL   =   2;
    const PRIORITY_HIGH     =   1;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \Mautic\SmsBundle\Entity\Sms
     */
    private $sms;

    /**
     * @var \Mautic\LeadBundle\Entity\LeadList
     */
    private $segment;

    /**
     * @var int
     */
    private $priority = 2;

    /**
     * @var int
     */
    private $maxAttempts = 3;

    /**
     * @var int
     */
    private $attempts = 0;

    /**
     * @var bool
     */
    private $success = false;

    /**
     * @var string
     */
    private $status = self::STATUS_PENDING;

    /**
     * @var null|\DateTime
     */
    private $datePublished;

    /**
     * @var null|\DateTime
     */
    private $scheduleDate;

    /**
     * @var null|\DateTime
     */
    private $lastAttempt;

    /**
     * @var null|\DateTime
     */
    private $dateSent;

    /**
     * @var array()
     */
    private $options = [];

    /**
     * @var bool
     */
    private $processed = false;

    /**
     * @var bool
     */
    private $failed = false;

    /**
     * @var bool
     */
    private $metadataUpdated = false;

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable("sms_queue")
            ->setCustomRepositoryClass('Mautic\SmsBundle\Entity\SmsQueueRepository')
            ->addIndex(['status'],'sms_status_search')
            ->addIndex(['date_sent'],'sms_date_sent')
            ->addIndex(['scheduled_date'],'sms_scheduled_date')
            ->addIndex(['priority'],'sms_priority')
            ->addIndex(['success'],'sms_success');

        $builder->addId();

        $builder->createManyToOne('sms','Mautic\SmsBundle\Entity\Sms')
            ->addJoinColumn('sms_id','id',true,false,'CASCADE')
            ->build();

        $builder->createManyToOne('segment','Mautic\LeadBundle\Entity\LeadList')
            ->addJoinColumn('segment_id','id',false,false,'CASCADE')
            ->build();

        $builder->createField('priority','smallint')
            ->columnName('priority')
            ->build();

        $builder->createField('maxAttempts','smallint')
            ->columnName('max_attempts')
            ->build();

        $builder->createField('attempts','smallint')
            ->columnName('attempts')
            ->build();

        $builder->createField('success','boolean')
            ->columnName('success')
            ->build();

        $builder->createField('status','string')
            ->columnName('status')
            ->build();

        $builder->createField('datePublished','datetime')
            ->columnName('date_published')
            ->nullable()
            ->build();

        $builder->createField('scheduleDate','datetime')
            ->columnName('scheduled_date')
            ->nullable()
            ->build();

        $builder->createField('lastAttempt','datetime')
            ->columnName('last_attempt')
            ->nullable()
            ->build();

        $builder->createField('dateSent','datetime')
            ->columnName('date_sent')
            ->nullable()
            ->build();

        $builder->createField('options','array')
            ->nullable()
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
     * @return Sms
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * @param Sms $sms
     */
    public function setSms($sms)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * @return \Mautic\LeadBundle\Entity\LeadList
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * @param \Mautic\LeadBundle\Entity\LeadList $segment
     */
    public function setSegment($segment)
    {
        $this->segment = $segment;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getMaxAttempts()
    {
        return $this->maxAttempts;
    }

    /**
     * @param int $maxAttempts
     */
    public function setMaxAttempts($maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * @return int
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param int $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime|null
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * @param \DateTime|null $datePublished
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;
    }

    /**
     * @return \DateTime|null
     */
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }

    /**
     * @param \DateTime|null $scheduleDate
     */
    public function setScheduleDate($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastAttempt()
    {
        return $this->lastAttempt;
    }

    /**
     * @param \DateTime|null $lastAttempt
     */
    public function setLastAttempt($lastAttempt)
    {
        $this->lastAttempt = $lastAttempt;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * @param \DateTime|null $dateSent
     */
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return bool
     */
    public function isProcessed()
    {
        return $this->processed;
    }

    /**
     * @param bool $processed
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return $this->failed;
    }

    /**
     * @param bool $failed
     */
    public function setFailed($failed)
    {
        $this->failed = $failed;
    }

    /**
     * @return bool
     */
    public function getMetadata()
    {
        return (isset($this->options['metadata'])) ? $this->options['metatdata'] : [];
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata = [])
    {
        $this->metadataUpdated = true;
        $this->options['metatdata'] = $metadata;
    }

    public function wasMetadataupdated()
    {
        return $this->metadataUpdated;
    }

}