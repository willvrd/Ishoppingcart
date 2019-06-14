<?php

namespace Modules\Ishoppingcart\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\Ishoppingcart\Entities\Coupon;

use Modules\Ishoppingcart\Repositories\CouponRepository;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentCouponRepository extends EloquentBaseRepository implements CouponRepository
{
   

	public function findByCode($code,$dateNow)
    {
    	return $this->model->where('code', $code)
    	->whereDate('from','<=',$dateNow)
    	->whereDate('to','>=',$dateNow)
    	->first();
    }

    public function findById($id,$dateNow){

    	return $this->model->where('id', $id)
    	->whereDate('from','<=',$dateNow)
    	->whereDate('to','>=',$dateNow)
    	->first();
    }

     public function find($id){

        return $this->model->find($id);
        
    }

}