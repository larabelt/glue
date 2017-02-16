import headingTemplate from 'belt/core/js/templates/heading2.html';
import createTemplate from './templates/create.html';
import formTemplate from './templates/form.html';
import CategoryForm from './form';

export default {
    components: {
        heading: { template: headingTemplate },
        categoryForm: {
            data() {
                return {
                    form: new CategoryForm({router: this.$router}),
                }
            },
            template: formTemplate,
        },
    },
    template: createTemplate
}