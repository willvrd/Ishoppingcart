<?php

return [
    'ishoppingcart.payments' => [
        'index' => 'ishoppingcart::payment.list',
        'create' => 'ishoppingcart::payment.create',
        'edit' => 'ishoppingcart::payment.edit',
        'destroy' => 'ishoppingcart::payment.destroy',
    ],
    'ishoppingcart.coupons' => [
        'index' => 'ishoppingcart::coupon.list',
        'create' => 'ishoppingcart::coupon.create',
        'edit' => 'ishoppingcart::coupon.edit',
        'destroy' => 'ishoppingcart::coupon.destroy',
    ],
    'ishoppingcart.orders' => [
        'index' => 'ishoppingcart::order.list',
        'create' => 'ishoppingcart::order.create',
        'edit' => 'ishoppingcart::order.edit',
        'destroy' => 'ishoppingcart::order.destroy',
    ],

    'ishoppingcart.orderCoupons' => [
        'index' => 'ishoppingcart::orderCoupon.list',
    ],

    'ishoppingcart.transactions' => [
        'index' => 'ishoppingcart::transaction.list',
        'create' => 'ishoppingcart::transaction.create',
        'edit' => 'ishoppingcart::transaction.edit',
        'destroy' => 'ishoppingcart::transaction.destroy',
    ],

    'ishoppingcart.orderItems' => [
        'index' => 'ishoppingcart::orderItem.list',
        'create' => 'ishoppingcart::orderItem.create',
        'edit' => 'ishoppingcart::orderItem.edit',
        'destroy' => 'ishoppingcart::orderItem.destroy',
    ],
];
