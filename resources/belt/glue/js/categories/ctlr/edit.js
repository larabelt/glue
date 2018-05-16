import shared from 'belt/glue/js/categories/ctlr/shared';
import Table from 'belt/glue/js/categories/table';

import parentCategories from 'belt/glue/js/categories/ctlr/index-table';
import categoryForm from 'belt/glue/js/categories/ctlr/category-form';

import edit_html from 'belt/glue/js/categories/templates/edit.html';
import form_html from 'belt/glue/js/categories/templates/form.html';

export default {
    mixins: [shared],
    components: {
        tab: categoryForm,
    },
    template: edit_html,
}