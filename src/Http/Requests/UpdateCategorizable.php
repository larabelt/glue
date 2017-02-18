<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

/**
 * Class UpdateCategorizable
 * @package Belt\Glue\Http\Requests
 */
class UpdateCategorizable extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'move' => 'required_with:position_entity_id|in:before,after',
            'position_entity_id' => [
                'required_with:move',
                'exists:categories,id',
                $this->ruleExists('categorizables', 'category_id', ['categorizable_id', 'categorizable_type'])
            ],
        ];
    }

}