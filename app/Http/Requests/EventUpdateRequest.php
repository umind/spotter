<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            // 'products' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png',
            'title' => 'required',
            'auctioner' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            // 'auction_begins' => 'required',
            'auction_deadline' => 'required',
            // 'view_dates' => 'required',
            // 'description' => 'required',
        ];
    }
}
