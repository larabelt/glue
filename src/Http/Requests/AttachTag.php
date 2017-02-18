<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class AttachTag
 * @package Belt\Glue\Http\Requests
 */
class AttachTag extends FormRequest
{


    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:tags,id',
        ];
    }

}