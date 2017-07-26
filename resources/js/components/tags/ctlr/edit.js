
// helpers
import Form from 'belt/glue/js/components/tags/form';

// templates make a change
import heading_html from 'belt/core/js/templates/heading.html';
import edit_html from 'belt/glue/js/components/tags/templates/edit.html';
import form_html from 'belt/glue/js/components/tags/templates/form.html';

export default {
    components: {
        heading: {template: heading_html},
        edit: {
            data() {
                return {
                    form: new Form(),
                }
            },
            mounted() {
                this.form.show(this.$route.params.id);
            },
            template: form_html,
        },
    },
    template: edit_html,
}