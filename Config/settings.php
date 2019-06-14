<?php

return [
    /*
    'coupons-qty' => [
        'description'  => 'ishoppingcart::common.settings.couponsQuantity',
        'view'         => 'number',
        'default'      => 10,
        'translatable' => false
    ],
    */
    'code-lenght' => [
        'description'  => 'ishoppingcart::common.settings.codeLenght',
        'view'         => 'number',
        'default'      => 8,
        'translatable' => false
    ],
    'coupon-format' => [
        'description'  => 'ishoppingcart::common.settings.couponFormat',
         'options' => [
		       'alphanumeric' => 'ishoppingcart::common.settings.alphanumeric',
		       'numeric' => 'ishoppingcart::common.settings.numeric',
		       'alphabetic' => 'ishoppingcart::common.settings.alphabetic',
		   ],
   		//'view' => 'radio',
        'view' => 'ishoppingcart::admin.fields.setting_radio',
        'translatable' => false
    ],
    'code-prefix' => [
        'description'  => 'ishoppingcart::common.settings.codePrefix',
        'view'         => 'text',
        'translatable' => false
    ],
    'code-sufix' => [
        'description'  => 'ishoppingcart::common.settings.codeSufix',
        'view'         => 'text',
        'translatable' => false
    ],
    'dash-every' => [
        'description'  => 'ishoppingcart::common.settings.dashEvery',
        'view'         => 'number',
        'default'      => 4,
        'translatable' => false
    ],

    'orderitems-tax' => [
        'description'  => 'ishoppingcart::common.settings.orderitemsTax',
        'view'         => 'number',
        'default'      => 0,
        'translatable' => false
    ],


    
];
