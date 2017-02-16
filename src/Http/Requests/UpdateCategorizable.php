<?php
namespace Belt\Glue\Http\Requests;

use Belt\Core\Http\Requests\FormRequest;

class UpdateCategorizable extends FormRequest
{

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