import shared from './shared';
import Form from '../form';
import categoryForm from './category-form';

// templates make a change
import heading_html from 'belt/core/js/templates/heading.html';
import form_html from '../templates/form.html';
import create_html from '../templates/create.html';

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