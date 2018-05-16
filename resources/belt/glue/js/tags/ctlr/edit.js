// helpers
import Form from 'belt/glue/js/tags/form';

// templates make a change

import edit_html from 'belt/glue/js/tags/templates/edit.html';
import form_html from 'belt/glue/js/tags/templates/form.html';

export default {
    data() {
        return {
            form: new Form(),
        }
    },
    mounted() {
        this.form.show(this.$route.params.id);
    },
    components: {
        edit: {
            data() {
                return {
                    form: this.$parent.form,
                }
            },
            template: form_html,
        },
    },
    template: edit_html,
}