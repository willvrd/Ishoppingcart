<?php

namespace Modules\Ishoppingcart\Repositories\Eloquent;

use Modules\Ishoppingcart\Repositories\OrderCouponRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentOrderCouponRepository extends EloquentBaseRepository implements OrderCouponRepository
{
  

	public function deleteOrderCoupon($orderID, $couponID){

        return $this->model->where('order_id',$orderID)
        ->where('coupon_id',$couponID)
        ->delete();

    }

}