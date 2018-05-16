// helpers
import Form from 'belt/glue/js/tags/form';

// templates make a change

import form_html from 'belt/glue/js/tags/templates/form.html';
import create_html from 'belt/glue/js/tags/templates/create.html';

export default {
    components: {

        create: {
            data() {
                return {
                    form: new Form({router: this.$router}),
                }
            },
            template: form_html,
        },
    },
    template: create_html
}