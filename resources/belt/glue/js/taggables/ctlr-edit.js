// helpers
import Form from 'belt/glue/js/taggables/form';
import Table from 'belt/glue/js/taggables/table';

// templates
import index_html from 'belt/glue/js/taggables/templates/index.html';

export default {
    data() {
        return {
            detached: new Table({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
                query: {not: 1},
            }),
            form: new Form({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
            }),
            table: new Table({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
            }),
            search: false,
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
        browse() {
            this.search = !this.search;
            if (this.search && !this.detached.items.length) {
                this.detached.index();
            }
        },
        clear() {
            this.search = false;
            this.detached.query.q = '';
            this.detached.empty();
        },
        filter: _.debounce(function (query) {
            this.search = true;
            if (query) {
                query.page = 1;
                this.detached.updateQuery(query);
            }
            this.detached.index()
                .then(() => {

                });
        }, 250),
    },
    template: index_html
}