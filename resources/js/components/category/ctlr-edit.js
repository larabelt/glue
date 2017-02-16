import headingTemplate from 'belt/core/js/templates/heading2.html';
import editTemplate from './templates/edit.html';
import formTemplate from './templates/form.html';
import Form from './form';

export default {
    components: {
        heading: { template: headingTemplate },
        categoryForm: {
            data() {
                return {
                    form: new Form(),
                }
            },
            mounted() {
                this.form.show(this.$route.params.id);
            },
            template: formTemplate,
        },
    },
    template: editTemplate,
}