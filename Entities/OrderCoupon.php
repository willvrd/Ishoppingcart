<?php

namespace Modules\Ishoppingcart\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Bcrud\Support\Traits\CrudTrait;
use Modules\Ishoppingcart\Entities\Feature;

use Laracasts\Presenter\PresentableTrait;
use Modules\Ishoppingcart\Presenters\PostPresenter;

class OrderCoupon extends Model
{
    use CrudTrait,  PresentableTrait;

    protected $table = 'ishoppingcart__order__coupon';

    protected $fillable = ['coupon_id','order_id','value','options'];
    //protected $presenter = PostPresenter::class;
    protected $fakeColumns = ['options'];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
   
    protected $casts = [
        'options' => 'array'
    ];

    
    public function getOptionsAttribute($value) {

        return json_decode(json_decode($value));

    }

   

}