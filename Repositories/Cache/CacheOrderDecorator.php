<?php

namespace Modules\Ishoppingcart\Repositories\Cache;

use Modules\Ishoppingcart\Repositories\OrderRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderDecorator extends BaseCacheDecorator implements OrderRepository
{
    public function __construct(OrderRepository $order)
    {
        parent::__construct();
        $this->entityName = 'order';
        $this->repository = $order;
    }
}
