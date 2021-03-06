<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
      //  $id = $this->route('account.update');
        return [
            'firstName'=>'required|string|max:255',
            'lastName'=>'required|string|max:255',
            'address'=>'required|string|max:255',
            'phoneNumber'=>'required|string|min:10|unique:users,phoneNumber,' . $this->id . ',id',
            'email'=>'required|regex:/(.+)@(.+)\.(.+)/i|email|unique:users,email,' . $this->id . ',id'

        ];
    }
}
