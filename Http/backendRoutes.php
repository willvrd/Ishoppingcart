<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/ishoppingcart'], function (Router $router) {

    \CRUD::resource('ishoppingcart','payment', 'PaymentController');
    \CRUD::resource('ishoppingcart','coupon', 'CouponController');
    \CRUD::resource('ishoppingcart','order', 'OrderController');

    \CRUD::resource('ishoppingcart','orderCoupon', 'OrderCouponController');

    \CRUD::resource('ishoppingcart','transaction', 'TransactionController');
    \CRUD::resource('ishoppingcart','orderItems', 'OrderItemsController');

});