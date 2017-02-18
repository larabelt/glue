<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class AttachCategory
 * @package Belt\Glue\Http\Requests
 */
class AttachCategory extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:categories,id',
        ];
    }

}