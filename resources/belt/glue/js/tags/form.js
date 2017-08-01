import BaseForm from 'belt/core/js/helpers/form';
import BaseService from 'belt/core/js/helpers/service';

class TagForm extends BaseForm {

    constructor(options = {}) {
        super(options);
        this.service = new BaseService({baseUrl: '/api/v1/tags/'});
        this.routeEditName = 'tags.edit';
        this.setData({
            id: '',
            name: '',
            slug: '',
            body: '',
        })
    }

}

export default TagForm;