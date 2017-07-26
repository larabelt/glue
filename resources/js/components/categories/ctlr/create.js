import shared from 'belt/glue/js/components/categories/ctlr/shared';
import Form from 'belt/glue/js/components/categories/form';
import categoryForm from 'belt/glue/js/components/categories/ctlr/category-form';

// templates make a change
import heading_html from 'belt/core/js/templates/heading.html';
import form_html from 'belt/glue/js/components/categories/templates/form.html';
import create_html from 'belt/glue/js/components/categories/templates/create.html';

export default {
    mixins: [shared],
    components: {
        heading: {template: heading_html},
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