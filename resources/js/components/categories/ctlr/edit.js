import shared from './shared';

import Table from '../table';

import parentCategories from './index-table';
import categoryForm from './category-form';

import edit_html from '../templates/edit.html';
import form_html from '../templates/form.html';

export default {
    mixins: [shared],
    components: {
        tab: categoryForm,
        tab1: {
            data() {
                return {
                    form: this.$parent.form,
                    parentCategory: this.$parent.parentCategory,
                    search: false,
                    table: new Table({router: this.$router}),
                }
            },
            mounted() {
                //this.form.show(this.$route.params.id);
            },
            methods: {
                toggle() {
                    if (!this.search) {
                        this.table.index();
                    }
                    this.search = !this.search;
                }
            },
            components: {
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
        },
    },
    template: edit_html,
}