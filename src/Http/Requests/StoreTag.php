<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class StoreTag
 * @package Belt\Glue\Http\Requests
 */
class StoreTag extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

}