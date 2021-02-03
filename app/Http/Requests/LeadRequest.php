<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_email' => 'required|email|max:255|regex:/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',
            'customer_phone1' => 'required|numeric',
            'cmb_category' => 'required',
            'cmb_area' => 'required',
            'cmb_project_name' => 'required',
            'cmb_size' => 'required',
            'Sub_Source' => 'required',
            'Source' => 'required' 
        ];
    }


    public function messages()
    {
        return [
            'customer_first_name.required' => 'Customer Name is required.',
            'customer_last_name.required' => 'Customer Last is required.',
            'customer_email.required' => 'Customer Email is required.',
            'customer_phone1.required' => 'You did not select any Phone Number.',
            'cmb_category.required' => 'You did not select any Project Category.',
            'cmb_area.required' => 'You did not select any Project Area.',
            'cmb_project_name.required' => 'You did not select any Project Name .',
            'cmb_size.required' => 'You did not select any Flat Size. ',
            'Sub_Source.required' => 'You did not select any sub source.',
            'Source.required' => 'You did not select any source.',

        ];
    }
}
