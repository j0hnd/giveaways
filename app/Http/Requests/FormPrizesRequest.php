<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormPrizesRequest extends FormRequest
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
        switch ($this->method()) {
            case "GET":
            case "DELETE":
                return [];
                break;

            case "POST":
                $this->sanitize();
                return [
//                    'name'   => 'required|min:5|max:100|unique:raffle_prizes',
                    'name'   => 'required|min:5|max:100',
                    'amount' => 'required|numeric',
                    'image'  => 'image|mimes:jpeg,jpg,png|max:2048'
                ];
                break;

//            case "PUT":
//            case "PATCH":
//                $this->sanitize();
//                return [
//                    'name'   => 'required|min:5|max:100',
//                    'amount' => 'required|numeric',
//                    'image'  => 'image:mimes:jpeg,jpg,png,gif|max:2048'
//                ];
//                break;

            default:
                break;
        }
    }

    /**
     * Sanitize input fields
     *
     */
    public function sanitize()
    {
        $input = $this->all();
        $input['name']   = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $input['amount'] = filter_var($input['amount'], FILTER_SANITIZE_NUMBER_FLOAT);

        $this->replace($input);
    }
}
