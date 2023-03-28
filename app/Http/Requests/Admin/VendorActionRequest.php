<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vendor;

class VendorActionRequest extends FormRequest
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
        $vendor = Vendor::find($this->get('vendor_id'));
        return [
            'vendor_id' => $vendor ? 'nullable' : 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'vendor_id.required' => 'It appears this vendor does not exist. Please try again later or contact technical support.'
        ];
    }
}
