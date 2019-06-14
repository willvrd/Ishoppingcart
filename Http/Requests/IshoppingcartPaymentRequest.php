<?php

namespace Modules\Ishoppingcart\Http\Requests;

use App\Http\Requests\Request;

class IshoppingcartPaymentRequest extends \Modules\Bcrud\Http\Requests\CrudRequest
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
            'description' => 'required|min:5',
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
            'name.required' => trans('ishoppingcart::payment.messages.name is required'),
            'name.min:2'=> trans('ishoppingcart::payment.messages.name min 2'),
            'description.required'=> trans('ishoppingcart::payment.messages.description is required'),
            'description.min:5'=> trans('ishoppingcart::payment.messages.description min 5'),
        ];
        
    }
}