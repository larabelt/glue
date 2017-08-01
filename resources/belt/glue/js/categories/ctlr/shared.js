// helpers
import Form from 'belt/glue/js/categories/form';

// templates make a change
import heading_html from 'belt/core/js/templates/heading.html';
import tabs_html from 'belt/glue/js/categories/templates/tabs.html';
import edit_html from 'belt/glue/js/categories/templates/edit.html';

export default {
    data() {
        return {
            form: new Form(),
            parentCategory: new Form(),
            morphable_type: 'categories',
            morphable_id: this.$route.params.id,
        }
    },
    components: {
        heading: {template: heading_html},
        tabs: {template: tabs_html},
    },
    mounted() {
        this.form.show(this.morphable_id)
            .then(() => {
                if (this.form.parent_id) {
                    console.log('parent');
                    this.parentCategory.show(this.form.parent_id);
                }
            });
    },
    template: edit_html,
}