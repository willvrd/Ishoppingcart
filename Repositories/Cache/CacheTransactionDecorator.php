<?php

namespace Modules\Ishoppingcart\Repositories\Cache;

use Modules\Ishoppingcart\Repositories\TransactionRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheTransactionDecorator extends BaseCacheDecorator implements TransactionRepository
{
    public function __construct(TransactionRepository $trasanction)
    {
        parent::__construct();
        $this->entityName = 'transaction';
        $this->repository = $transaction;
    }
}
