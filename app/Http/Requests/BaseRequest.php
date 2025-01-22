<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
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
     * @param Validator $validator
     * @return mixed
     */

    protected function failedValidation(Validator $validator):mixed
    {
        throw new HttpResponseException(
            failresponse($validator->errors()->first())
        );
    }

//    /**
//     * @param array $fields
//     * @return array
//     */
//    protected function langRules(array $fields = []): array
//    {
//        $fields = empty($fields) ? ['name', 'description'] : $fields;
//        $defaultRules = 'required|string';
//
//        return collect(config('lang'))->flatMap(function ($lang) use ($fields , $defaultRules) {
//            $rules = [];
//            foreach ($fields as $field => $rule) {
//
//                if (is_int($field)) {
//                    $field = $rule;
//                    $rule = $defaultRules;
//                }
//
//                $rules["$field.$lang"] = $rule;
//            }
//            return $rules;
//        })->toArray();
//    }
}
