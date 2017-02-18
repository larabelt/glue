<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class UpdateTag
 * @package Belt\Glue\Http\Requests
 */
class UpdateTag extends FormRequest
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