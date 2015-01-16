<?php

namespace OlesKashchenko\AlfabankApi;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Yandex\Translate\Translator;
use Yandex\Translate\Exception;

class AlfabankApi {

    private $response;
    private $serverUrl = '';
    private $currency;
    private $language = 'ru';
    private $failUrl;
    private $successUrl;


    public function __construct()
    {
        $this->serverUrl = Config::get('alfabank-api::payment.server_url');
        $this->currency = Config::get('alfabank-api::payment.currency');
        $this->language = Config::get('alfabank-api::payment.language');
        $this->failUrl = Config::get('alfabank-api::payment.fail_url');
        $this->successUrl = Config::get('alfabank-api::payment.success_url');
    } // end __construct


}

