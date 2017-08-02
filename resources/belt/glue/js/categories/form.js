import BaseForm from 'belt/core/js/helpers/form';
import BaseService from 'belt/core/js/helpers/service';

class CategoryForm extends BaseForm {

    constructor(options = {}) {
        super(options);
        this.service = new BaseService({baseUrl: '/api/v1/categories/'});
        this.routeEditName = 'categories.edit';
        this.setData({
            id: '',
            parent_id: null,
            is_active: 0,
            name: '',
            slug: '',
            body: '',
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            full_name: '',
            template: '',
        })
    }

}

export default CategoryForm;