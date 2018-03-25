<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormRaffleEntryRequest extends FormRequest
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
        $this->sanitize();

        switch ($this->method()) {
            case "GET":
            case "DELETE":
                return [];
                break;

            case "POST":
                return [
                    'email'                => 'required|email',
                    'raffle_id'            => 'required',
                    'g-recaptcha-response' => 'required|recaptcha'
                ];
                break;

            case "PUT":
            case "PATCH":
                return [
                    'email'     => 'required|email',
                    'raffle_id' => 'required'
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
        $input['email']       = filter_var($input['email'], FILTER_SANITIZE_EMAIL);

        $this->replace($input);
    }
}
