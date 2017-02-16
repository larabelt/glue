<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

class StoreCategory extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

}