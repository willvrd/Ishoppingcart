<?php

use Illuminate\Routing\Router;



    $router->group(['prefix' => trans('ishoppingcart::common.uri')], function (Router $router) {

        $locale = LaravelLocalization::setLocale() ?: App::getLocale();
        $router->post('find_coupon', [
            'uses' => 'PublicController@findCoupon',
        ]);

        $router->get('/', [
            'as' => $locale . '.checkout',
            'uses' => 'PublicController@index',
        ]);

        $router->post('/checkout_process', [
            'as' => 'ishoppingcart.checkout_process',
            'uses' => 'PublicController@checkoutProcess',
        ]);

        $router->get('/paypal_msj', [
            'as' => 'ishoppingcart.paypal_msj',
            'uses' => 'PublicController@paypalMSJ',
        ]);

        $router->post('/paypal_ipn', [
            'as' => 'ishoppingcart.paypal_ipn',
            'uses' => 'PublicController@paypalIPN',
        ]);


        $router->post('/giftcard', [
            'as' => $locale . '.checkout.giftcard',
            'uses' => 'PublicController@checkoutGiftcard',
        ]);

        $router->post('/giftcard/checkout_process', [
            'as' => 'ishoppingcart.giftcard.checkout_process',
            'uses' => 'PublicController@checkoutProcessGiftcard',
        ]);


    });

    $router->group(['prefix'=>'redsys'],function (Router $router){
        $locale = LaravelLocalization::setLocale() ?: App::getLocale();

        $router->get('/', [
            'as' => 'redsys',
            'uses' => 'RedsysController@index',
        ]);
        $router->post('/notification', [
            'as' => 'redsys.notification',
            'uses' => 'RedsysController@notification',
        ]);
        $router->get('/ok', [
            'as' => 'redsys.ok',
            'uses' => 'RedsysController@ok',
        ]);
        $router->get('/ko', [
            'as' => 'redsys.ko',
            'uses' => 'RedsysController@ko',
        ]);
    });

    $router->group(['prefix'=>'paypal'],function (Router $router){
        $locale = LaravelLocalization::setLocale() ?: App::getLocale();

        $router->get('/', [
            'as' => 'paypal',
            'uses' => 'PaypalController@index',
        ]);
        $router->get('/ok', [
            'as' => 'paypal.ok',
            'uses' => 'PaypalController@store',
        ]);
        $router->get('/ko', [
            'as' => 'paypal.ko',
            'uses' => 'PaypalController@ko',
        ]);
    });

