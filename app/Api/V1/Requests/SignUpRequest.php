<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.sign_up.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
