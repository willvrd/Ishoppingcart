<?php

namespace Modules\Ishoppingcart\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Bcrud\Support\Traits\CrudTrait;
use Modules\Ishoppingcart\Entities\Feature;

use Laracasts\Presenter\PresentableTrait;
use Modules\Ishoppingcart\Presenters\PostPresenter;

class Order extends Model
{
    use CrudTrait,  PresentableTrait;

    protected $table = 'ishoppingcart__orders';

    protected $fillable = ['type','amount','status','user_id','options'];
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

     public function orderitems()
    {
        return $this->hasMany(OrderItems::class);
    }

     public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

     public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'ishoppingcart__order__coupon');
    }
    

    public function user()
    {
        $driver = config('asgard.user.config.driver');

        return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
    }


    public function getOptionsAttribute($value) {

        return json_decode(json_decode($value));

    }

    public function setTypeAttribute($value){

       if(isset($value)){
           $this->attributes['type']= $value;
       }else{
           $this->attributes['type']= "null";
       }


    }


}