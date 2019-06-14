<?php

namespace Modules\Ishoppingcart\Repositories\Eloquent;

use Modules\Ishoppingcart\Repositories\OrderItemsRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentOrderItemsRepository extends EloquentBaseRepository implements OrderItemsRepository
{
    
    public function findByOrder($orderID){

    	return $this->model->where("order_id",$orderID)->first();

    }

    public function findByProductId($productID){

    	return $this->model->where("product_id",$productID)->first();
    	
    }

}