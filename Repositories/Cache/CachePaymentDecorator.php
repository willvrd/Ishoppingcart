<?php

namespace Modules\Ishoppingcart\Repositories\Cache;

use Modules\Ishoppingcart\Repositories\PaymentRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePaymentDecorator extends BaseCacheDecorator implements PaymentRepository
{
    public function __construct(PaymentRepository $payment)
    {
        parent::__construct();
        $this->entityName = 'payment';
        $this->repository = $payment;
    }
}
