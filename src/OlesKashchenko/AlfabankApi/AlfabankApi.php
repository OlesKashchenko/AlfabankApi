<?php

namespace OlesKashchenko\AlfabankApi;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Yandex\Translate\Translator;
use Yandex\Translate\Exception;

class AlfabankApi
{
    // system settings
    private $isTestMode = true;
    private $serviceUrl = '';
    private $segmentUrl = '';
    private $failUrl;
    private $successUrl;
    private $userName;
    private $password;
    private $currency;
    private $language = 'ru';
    private $response;


    public function __construct()
    {
        $this->isTestMode = Config::get('alfabank-api::payment.test_mode');

        if ($this->isTestMode) {
            $this->serviceUrl = Config::get('alfabank-api::payment.test_service_url');
        } else {
            $this->serviceUrl = Config::get('alfabank-api::payment.prod_service_url');
        }

        $this->userName = Config::get('alfabank-api::payment.username');
        $this->password = Config::get('alfabank-api::payment.password');
        $this->failUrl = Config::get('alfabank-api::payment.fail_url');
        $this->successUrl = Config::get('alfabank-api::payment.success_url');
        $this->currency = Config::get('alfabank-api::payment.currency');
        $this->language = Config::get('alfabank-api::payment.language');
    } // end __construct

    public function getServiceUrl()
    {
        if (!$this->serviceUrl) {
            throw new \RuntimeException('Alfabank: service URL is not set');
        }

        return $this->serviceUrl;
    } // end getServiceUrl

    public function getSegmentUrl()
    {
        if (!$this->segmentUrl) {
            throw new \RuntimeException('Alfabank: segment URL is not set');
        }

        return $this->segmentUrl;
    } // end getSegmentUrl

    public function getSuccessUrl()
    {
        if (!$this->successUrl) {
            throw new \RuntimeException('Alfabank: return URL is not set');
        }

        return $this->successUrl;
    } // end getSuccessUrl

    public function getFailUrl()
    {
        if (!$this->failUrl) {
            throw new \RuntimeException('Alfabank: fail URL is not set');
        }

        return $this->failUrl;
    } // end getFailUrl

    public function getUsername()
    {
        if (!$this->userName) {
            throw new \RuntimeException('Alfabank: username is not set');
        }

        return $this->userName;
    } // end getUsername

    public function getPassword()
    {
        if (!$this->password) {
            throw new \RuntimeException('Alfabank: password is not set');
        }

        return $this->password;
    } // end getPassword

    public function getCurrency()
    {
        if (!$this->currency) {
            throw new \RuntimeException('Alfabank: currency is not set');
        }

        return $this->currency;
    } // end getCurrency

    public function getLanguage()
    {
        if (!$this->language) {
            throw new \RuntimeException('Alfabank: language is not set');
        }

        return $this->language;
    } // end getLanguage

    public function doSimpleOrderRegister(array $orderData)
    {
        $this->segmentUrl = 'register.do';

        $params = $this->getSimpleOrderRegisterParams($orderData);
        $response = $this->doCurlRequest($params);
        $this->response = $this->doResponseDecode($response);

        return $this->response;
    } // end doSimpleOrderRegister

    public function doSimpleOrderStatus($orderId)
    {
        $this->segmentUrl = 'getOrderStatus';

        $params = $this->getSimpleOrderStatusParams($orderId);
        $response = $this->doCurlRequest($params);
        $this->response = $this->doResponseDecode($response);

        return $this->response;
    } // end doSimpleOrderStatus

    public function doSimpleOrderStatusExt($orderId)
    {
        $this->segmentUrl = 'getOrderStatusExtended';

        $params = $this->getSimpleOrderStatusExtParams($orderId);
        $response = $this->doCurlRequest($params);
        $this->response = $this->doResponseDecode($response);

        return $this->response;
    } // end doSimpleOrderStatusExt

    public function isOk()
    {
        return !isset($this->response['errorCode']) || !$this->response['errorCode'];
    } // end isOk

    // FIXME:
    public function getStatusMessage()
    {
        switch ($this->response['errorCode']) {
            case '1':
                return '';
            case '2':
                return '';
            case '3':
                return '';
            case '4':
                return '';
            case '5':
                return '';
            case '6':
                return '';
            case '7':
                return '';
            case '8':
                return '';
            case '9':
                return '';
            case '10':
                return '';
            default:
                throw new \RuntimeException('Alfabank: not implemented status code - '. $this->response['errorCode']);
        }
    } // end getStatusMessage

    private function getSimpleOrderRegisterParams($orderData)
    {
        $params = array(
            'userName'      => $this->getUsername(),
            'password'      => $this->getPassword(),
            'orderNumber'   => $orderData['id_order'],
            'amount'        => $orderData['amount'],
            'currency'      => $this->getCurrency(),
            'returnUrl'     => urlencode($this->getSuccessUrl()),
            'failUrl'       => urlencode($this->getFailUrl())
        );

        if (isset($orderData['description'])) {
            $params['description'] = $orderData['description'];
        }

        return $params;
    } // end getSimpleRegisterOrderParams

    private function getSimpleOrderStatusParams($orderId)
    {
        $params = array(
            'userName'      => $this->getUsername(),
            'password'      => $this->getPassword(),
            'orderId'       => $orderId,
            'language'      => $this->getLanguage()
        );

        return $params;
    } // end getSimpleOrderStatusParams

    private function getSimpleOrderStatusExtParams($orderId)
    {
        $params = array(
            'userName'      => $this->getUsername(),
            'password'      => $this->getPassword(),
            'orderNumber'   => $orderId,
            'language'      => $this->getLanguage()
        );

        return $params;
    } // end getSimpleOrderStatusExtParams

    private function doCurlRequest($data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->doPrepareRequestParams($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        curl_setopt($ch, CURLOPT_URL, $this->getServiceUrl() . $this->getSegmentUrl());

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    } // end doCurlRequest

    private function doPrepareRequestParams($params)
    {
        return http_build_query($params);
    } // end doPrepareRequestParams

    private function doResponseDecode($encodedResponse)
    {
        return json_decode($encodedResponse, true);
    } // end doResponseDecode
}

