import Table from 'belt/glue/js/categories/table';
import templateDropdown from 'belt/content/js/templates';
import parentCategories from 'belt/glue/js/categories/ctlr/index-table';
import form_html from 'belt/glue/js/categories/templates/form.html';

export default {
    data() {
        return {
            form: this.$parent.form,
            parentCategory: this.$parent.parentCategory,
            search: false,
            table: new Table({router: this.$router}),
        }
    },
    methods: {
        toggle() {
            if (!this.search) {
                this.table.index();
            }
            this.search = !this.search;
        },
        clearParentCategory() {
            this.form.parent_id = null;
            this.parentCategory.reset();
            this.search = false;
        }
    },
    components: {
        templateDropdown,
        parentCategories: {
            mixins: [parentCategories],
            methods: {
                confirm(category) {
                    if (category.id != this.$parent.form.parent_id) {
                        this.$parent.form.parent_id = category.id;
                        this.$parent.search = false;
                        this.$parent.parentCategory.setData(category);
                    }
                }
            }
        }
    },
    template: form_html,
}