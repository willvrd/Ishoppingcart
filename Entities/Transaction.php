<?php

namespace Modules\Ishoppingcart\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Bcrud\Support\Traits\CrudTrait;
use Modules\Ishoppingcart\Entities\Feature;

use Laracasts\Presenter\PresentableTrait;
use Modules\Ishoppingcart\Presenters\PostPresenter;

class Transaction extends Model
{
    use CrudTrait,  PresentableTrait;

    protected $table = 'ishoppingcart__transaction';

    protected $fillable = ['order_id','payment_id','status', 'amount','options'];
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

   
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

     public function payment()
    {
        return $this->belongsTo(Payment::class);
    }


    public function getOptionsAttribute($value) {

        return json_decode(json_decode($value));

    }


}
