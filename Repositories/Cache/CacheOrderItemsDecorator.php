<?php

namespace Modules\Ishoppingcart\Repositories\Cache;

use Modules\Ishoppingcart\Repositories\OrderItemsRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderItemsDecorator extends BaseCacheDecorator implements OrderItemsRepository
{
    public function __construct(OrderItemsRepository $orderItems)
    {
        parent::__construct();
        $this->entityName = 'orderItems';
        $this->repository = $orderItems;
    }
}
