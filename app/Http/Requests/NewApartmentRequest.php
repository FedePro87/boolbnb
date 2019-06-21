<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewApartmentRequest extends FormRequest
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
            
            'title'=> '',
            'description'=> '',
            'price'=> '',
            'number_of_rooms'=> '',
            'bathrooms'=> '',
            'bedrooms'=> '',
            'square_meters'=> '',
            'address'=> '',
            'image'=> ''
        ];
    }
}
