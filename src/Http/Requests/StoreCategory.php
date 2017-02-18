<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class StoreCategory
 * @package Belt\Glue\Http\Requests
 */
class StoreCategory extends FormRequest
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