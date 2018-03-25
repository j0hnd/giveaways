<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormRafflesRequest extends FormRequest
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
//                    'name'              => 'required|min:5|max:100|unique:raffles',
                    'name'              => 'required|min:5|max:100',
                    'number_of_winners' => 'required|numeric',
                    'start_date'        => 'required',
                    'end_date'          => 'required',
                ];
                break;

            case "PUT":
            case "PATCH":
                $this->sanitize();
                return [
                    'name'              => 'required|min:5|max:100',
                    'number_of_winners' => 'required|numeric',
                    'start_date'        => 'required',
                    'end_date'          => 'required',
                ];
                break;

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
        $input['name']              = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $input['subtitle']          = filter_var($input['subtitle'], FILTER_SANITIZE_STRING);
        $input['number_of_winners'] = filter_var($input['number_of_winners'], FILTER_VALIDATE_INT);
        $input['start_date']        = filter_var($input['start_date'], FILTER_SANITIZE_STRING);
        $input['end_date']          = filter_var($input['end_date'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
