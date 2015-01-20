<?php

return array(
    'payment' => array(
        'test_mode'         => true,
        'test_service_url'  => 'https://test.paymentgate.ru/testpayment/rest/',
        'prod_service_url'  => 'https://paymentgate.ru/',
        'username'          => '',
        'password'          => '',
        'currency'          => '810',
        'language'          => 'ru',
        'success_url'       => '/alfa-success',
        'fail_url'          => '/alfa-fail',
    ),
);
