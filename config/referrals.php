<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Referral Reward
    |--------------------------------------------------------------------------
    |
    | The coupon automatically generated for a referrer once the person they
    | referred completes their first delivered order.
    |
    */

    'discount_type' => 'percentage', // 'percentage' or 'flat'

    'discount_value' => 10,

    'max_discount_amount' => 200,

    'validity_days' => 30,

];
