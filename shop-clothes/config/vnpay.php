<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPay Configuration
    |--------------------------------------------------------------------------
    |
    | Get your credentials from https://sandbox.vnpayment.vn/apis/
    | For production, change vnp_TmnCode, vnp_HashSecret and vnp_Url
    |
    */

    'vnp_TmnCode' => env('VNPAY_TMN_CODE', ''),
    'vnp_HashSecret' => env('VNPAY_HASH_SECRET', ''),
    'vnp_Url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_ReturnUrl' => env('VNPAY_RETURN_URL', env('APP_URL') . '/payment/vnpay/callback'),
    'vnp_Api' => env('VNPAY_API', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
];