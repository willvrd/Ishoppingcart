<?php

namespace Modules\Ishoppingcart\Repositories\Eloquent;

use Modules\Ishoppingcart\Repositories\PaymentRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentPaymentRepository extends EloquentBaseRepository implements PaymentRepository
{
   
	public function all()
    {
        return $this->model->orderBy('created_at', 'DESC')->paginate(12);
    }

    public function findById($id){
    	return $this->model->find($id);
    }


}