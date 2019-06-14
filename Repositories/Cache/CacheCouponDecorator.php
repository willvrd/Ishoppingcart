<?php

namespace Modules\Ishoppingcart\Repositories\Cache;

use Modules\Ishoppingcart\Repositories\CouponRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCouponDecorator extends BaseCacheDecorator implements CouponRepository
{
    public function __construct(CouponRepository $coupon)
    {
        parent::__construct();
        $this->entityName = 'coupon';
        $this->repository = $coupon;
    }
}
