<?php

namespace Modules\Ishoppingcart\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Bcrud\Support\Traits\CrudTrait;
use Modules\Ishoppingcart\Entities\Feature;

use Laracasts\Presenter\PresentableTrait;
use Modules\Ishoppingcart\Presenters\PostPresenter;

use DateTime;

class Coupon extends Model
{
    use CrudTrait,  PresentableTrait;

    protected $table = 'ishoppingcart__coupons';

    protected $fillable = ['name','code','type','cant','value','from','to','options','email'];
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


    public function orders()
    {
        return $this->belongsToMany(Order::class,'ishoppingcart__order__coupon');
    }
   
    public function getOptionsAttribute($value) {

        return json_decode(json_decode($value));

    }

    public function setFromAttribute($value){

        $format = 'Y-m-d';
        $date = DateTime::createFromFormat($format,$value);
        $this->attributes['from']= $date;

    }

    public function getFromAttribute($value){
    
        $format = 'Y-m-d';
        return date($format, strtotime($value));

    }


    public function setToAttribute($value){

        $format = 'Y-m-d';
        $date = DateTime::createFromFormat($format,$value);
        $this->attributes['to']= $date;

    }

    public function getToAttribute($value){
    
        $format = 'Y-m-d';
        return date($format, strtotime($value));

    }


}