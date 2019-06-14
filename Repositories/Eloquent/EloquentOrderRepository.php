<?php

namespace Modules\Ishoppingcart\Repositories\Eloquent;

use Modules\Ishoppingcart\Repositories\OrderRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentOrderRepository extends EloquentBaseRepository implements OrderRepository
{
    
	public function findById($id){

    	return $this->model->find($id);

    }

    public function updateOrderStatus($orderID,$status){

    	return $this->model->where("id",$orderID)->update(['status' => $status]);

    }

}