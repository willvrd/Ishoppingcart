<?php

namespace Modules\Ishoppingcart\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\Ishoppingcart\Entities\Coupon;
use Modules\Ishoppingcart\Entities\Order;
use Modules\Ishoppingcart\Entities\Payment;
use Modules\Ishoppingcart\Entities\Transaction;
use Modules\Ishoppingcart\Entities\OrderItems;
use Modules\Ishoppingcart\Entities\OrderCoupon;

use Modules\Ishoppingcart\Repositories\CouponRepository;
use Modules\Ishoppingcart\Repositories\OrderRepository;
use Modules\Ishoppingcart\Repositories\PaymentRepository;
use Modules\Ishoppingcart\Repositories\TransactionRepository;
use Modules\Ishoppingcart\Repositories\OrderItemsRepository;
use Modules\Ishoppingcart\Repositories\OrderCouponRepository;

use Modules\Ishoppingcart\Repositories\Cache\CacheCouponDecorator;
use Modules\Ishoppingcart\Repositories\Cache\CacheOrderDecorator;
use Modules\Ishoppingcart\Repositories\Cache\CachePaymentDecorator;
use Modules\Ishoppingcart\Repositories\Cache\CacheTransactionDecorator;
use Modules\Ishoppingcart\Repositories\Cache\CacheOrderItemsDecorator;
use Modules\Ishoppingcart\Repositories\Cache\CacheOrderCouponDecorator;

use Modules\Ishoppingcart\Repositories\Eloquent\EloquentCouponRepository;
use Modules\Ishoppingcart\Repositories\Eloquent\EloquentOrderRepository;
use Modules\Ishoppingcart\Repositories\Eloquent\EloquentPaymentRepository;
use Modules\Ishoppingcart\Repositories\Eloquent\EloquentTransactionRepository;
use Modules\Ishoppingcart\Repositories\Eloquent\EloquentOrderItemsRepository;
use Modules\Ishoppingcart\Repositories\Eloquent\EloquentOrderCouponRepository;


use Modules\Core\Traits\CanPublishConfiguration;

class IshoppingcartServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    public function boot()
    {
        $this->publishConfig('ishoppingcart', 'config');
        $this->publishConfig('ishoppingcart', 'settings');
        $this->publishConfig('ishoppingcart', 'permissions');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {

        $this->app->bind(CouponRepository::class, function () {
            $repository = new EloquentCouponRepository(new Coupon());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheCouponDecorator($repository);
        });

        $this->app->bind(OrderItemsRepository::class, function () {
            $repository = new EloquentOrderItemsRepository(new OrderItems());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheOrderItemsDecorator($repository);
        });

        $this->app->bind(OrderRepository::class, function () {
            $repository = new EloquentOrderRepository(new Order());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheOrderDecorator($repository);
        });

        $this->app->bind(PaymentRepository::class, function () {
            $repository = new EloquentPaymentRepository(new Payment());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CachePaymentDecorator($repository);
        });

        $this->app->bind(TransactionRepository::class, function () {
            $repository = new EloquentTransactionRepository(new Transaction());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheTransactionDecorator($repository);
        });

        $this->app->bind(OrderCouponRepository::class, function () {
            $repository = new EloquentOrderCouponRepository(new OrderCoupon());

            if (config('app.cache') === false) {
                return $repository;
            }

            return new CacheOrderCouponDecorator($repository);
        });
       

    }
}
