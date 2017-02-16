<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

class AttachCategory extends FormRequest
{

    public function rules()
    {
        return [
            'id' => 'required|exists:categories,id',
        ];
    }

}