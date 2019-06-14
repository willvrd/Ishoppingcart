<?php

namespace Modules\Ishoppingcart\Repositories\Cache;

use Modules\Ishoppingcart\Repositories\OrderCouponRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderCouponDecorator extends BaseCacheDecorator implements OrderCouponRepository
{
    public function __construct(OrderCouponRepository $orderCoupon)
    {
        parent::__construct();
        $this->entityName = 'orderCoupon';
        $this->repository = $orderCoupon;
    }
}

