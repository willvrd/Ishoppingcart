<?php

namespace Modules\Ishoppingcart\Http\Requests;

use App\Http\Requests\Request;

class IshoppingcartCouponRequest extends \Modules\Bcrud\Http\Requests\CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
        return [
            'name' => 'required|min:2',
            'code' => 'required|min:2',
            'type' => 'required',
            'cant' => 'required|min:1',
            'value' => 'required|min:1',
           
            
        ];
       
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
       
        return [
            'name.required' => trans('ishoppingcart::coupon.messages.name is required'),
            'name.min:2'=> trans('ishoppingcart::coupon.messages.name min 2'),
            'code.required'=> trans('ishoppingcart::coupon.messages.code is required'),
            'code.min:2'=> trans('ishoppingcart::coupon.messages.code min 2'),
            'type.required'=> trans('ishoppingcart::coupon.messages.type is required'),
            'type.min:2'=> trans('ishoppingcart::coupon.messages.type min 2'),
            'cant.required'=> trans('ishoppingcart::coupon.messages.cant is required'),
            'cant.min:1'=> trans('ishoppingcart::coupon.messages.cant min 1'),
            'value.required'=> trans('ishoppingcart::coupon.messages.value is required'),
            'value.min:1'=> trans('ishoppingcart::coupon.messages.value min 1'),
            'from.required'=> trans('ishoppingcart::coupon.messages.from is required'),
            'from.min:8'=> trans('ishoppingcart::coupon.messages.from min 8'),
            'to.required'=> trans('ishoppingcart::coupon.messages.to is required'),
            'to.min:8'=> trans('ishoppingcart::coupon.messages.to min 8'),
        ];
        
    }
}