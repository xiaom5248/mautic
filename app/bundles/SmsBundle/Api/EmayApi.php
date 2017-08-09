<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-14
 * Time: 15:37
 */

namespace Mautic\SmsBundle\Api;


use Mautic\CoreBundle\Helper\PhoneNumberHelper;
use Mautic\PageBundle\Model\TrackableModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Monolog\Logger;

class EmayApi extends AbstractSmsApi
{
    /**
     * @var \Chxj1992\YimeiSms\App\Client;
     */
    protected $client;

    /**
     * @var Logger
     */
    protected $logger;


    /**
     * EmayApi constructor.
     * @param TrackableModel $pageTrackableModel
     * @param PhoneNumberHelper $phoneNumberHelper
     * @param IntegrationHelper $integrationHelper
     * @param Logger $logger
     */
    public function __construct(TrackableModel $pageTrackableModel, PhoneNumberHelper $phoneNumberHelper,IntegrationHelper $integrationHelper,Logger $logger)
    {
        $this->logger = $logger;

        $integration = $integrationHelper->getIntegrationObject('Emay');

        if($integration && $integration->getIntegrationSettings()->getIsPublished()) {
            $keys = $integration->getDecryptedApiKeys();

            $config =  [
                'gwUrl' => 'http://hprpt2.eucp.b2m.cn:8080/sdk/SDKService?wsdl',
                'serialNumber' => $keys['username'],
                'password' => $keys['password'],
                'sessionKey' => $keys['password'],
            ];
            $this->client = \Chxj1992\YimeiSms\App\ClientFactory::instance($config);
        }

        parent::__construct($pageTrackableModel);
    }

    /**
     * @param $number
     * @return mixed
     */
    protected function sanitizeNumber($number)
    {
        return $number;
    }

    public function sendSms($number, $content)
    {
        if($number == null){
            return false;
        }

        $ret = $this->client->sendSMS([$number],$content);
        if($ret == "0")
            return true;
        else
            return $ret;
    }
}