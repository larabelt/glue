import BaseForm from 'belt/core/js/helpers/form';
import BaseService from 'belt/core/js/helpers/service';

class TreeForm extends BaseForm {

    /**
     * Create a new Form instance.
     *
     * @param {object} options
     */
    constructor(options = {}) {
        super(options);

        let category_id = null;
        if (options.category.id) {
            category_id = options.category.id;
        }

        let baseUrl = `/api/v1/categories/${category_id}/tree/`;
        this.service = new BaseService({baseUrl: baseUrl});

        // data
        this.setData({
            neighbor_id: '',
            move: '',
        });
    }

}

export default TreeForm;