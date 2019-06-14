<?php

namespace Modules\Ishoppingcart\Repositories\Eloquent;

use Modules\Ishoppingcart\Repositories\TransactionRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentTransactionRepository extends EloquentBaseRepository implements TransactionRepository
{
    
    public function updateTransactionStatus($orderID,$status){

    	return $this->model->where("order_id",$orderID)->update(['status' => $status]);

    }

}