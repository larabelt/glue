import BaseForm from 'belt/core/js/helpers/form';
import BaseService from 'belt/core/js/helpers/service';

class CategoryForm extends BaseForm {

    constructor(options = {}) {
        super(options);
        this.service = new BaseService({baseUrl: '/api/v1/categories/'});
        this.routeEditName = 'categories.edit';
        this.setData({
            id: '',
            name: '',
            slug: '',
            body: '',
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
        })
    }

}

export default CategoryForm;