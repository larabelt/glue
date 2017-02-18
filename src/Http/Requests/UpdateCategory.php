<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class UpdateCategory
 * @package Belt\Glue\Http\Requests
 */
class UpdateCategory extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required',
        ];
    }

}