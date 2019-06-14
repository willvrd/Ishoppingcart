<?php

namespace Modules\Ishoppingcart\Http\Requests;

use App\Http\Requests\Request;

class IshoppingcartOrderRequest extends \Modules\Bcrud\Http\Requests\CrudRequest
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
            'type' => 'required|min:2',
            'amount' => 'required|min:1',
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
            'type.required' => trans('ishoppingcart::order.messages.type is required'),
            'type.min:2'=> trans('ishoppingcart::order.messages.type min 2'),
            'amount.required'=> trans('ishoppingcart::order.messages.amount is required'),
            'amount.min:1'=> trans('ishoppingcart::order.messages.amount min 1'),
        ];
        
    }
}