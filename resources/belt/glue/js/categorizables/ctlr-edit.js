import Form from 'belt/glue/js/categorizables/form';
import Table from 'belt/glue/js/categorizables/table';
//import categories from 'belt/glue/js/categories/ctlr/index-table';
import index_html from 'belt/glue/js/categorizables/templates/index.html';

export default {
    data() {
        return {
            detached: new Table({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
                query: {not: 1},
            }),
            table: new Table({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
            }),
            form: new Form({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
            }),
        }
    },
    mounted() {
        this.table.index();
    },
    methods: {
        attach(id) {
            this.form.setData({id: id});
            this.form.store()
                .then(response => {
                    this.table.index();
                    this.detached.index();
                })
        },
        clear() {
            this.detached.query.q = '';
        },
    },
    // components: {
    //     categories: {
    //         mixins: [categories],
    //         data() {
    //             return {
    //                 table: this.$parent.detached,
    //                 form: this.$parent.form,
    //             }
    //         },
    //         methods: {
    //             confirm(category) {
    //                 this.form.setData({id: category.id});
    //                 this.form.store()
    //                     .then(response => {
    //                         this.$parent.detached.query.q = '';
    //                         this.$parent.table.index();
    //                     })
    //             }
    //         }
    //     }
    // },
    template: index_html
}