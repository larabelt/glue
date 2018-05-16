import shared from 'belt/glue/js/categories/ctlr/shared';
import Form from 'belt/glue/js/categories/form';
import categoryForm from 'belt/glue/js/categories/ctlr/category-form';

// templates make a change

import form_html from 'belt/glue/js/categories/templates/form.html';
import create_html from 'belt/glue/js/categories/templates/create.html';

export default {
    mixins: [shared],
    components: {

        create: {
            mixins: [categoryForm],
            data() {
                return {
                    form: new Form({router: this.$router}),
                }
            },
        },
        create1: {
            data() {
                return {
                    form: new Form({router: this.$router}),
                    parentCategory: this.$parent.parentCategory,
                }
            },
            template: form_html,
        },
    },
    template: create_html
}