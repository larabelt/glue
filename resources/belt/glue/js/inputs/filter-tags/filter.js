import Table from 'belt/glue/js/tags/table';

import debounce from 'debounce';
import base from 'belt/core/js/inputs/filter-base';
import html from 'belt/glue/js/inputs/filter-tags/filter.html';

export default {
    mixins: [base],
    props: {
        //table: {default: null},
        //form: {default: null},
    },
    data() {
        return {
            detached: new Table({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
            }),
        };
    },
    computed: {
        tagId() {
            return _.map(this.attached, 'id').join(",");
        }
    },
    created() {

    },
    methods: {
        push(tag) {
            Vue.set(this.attached, tag.id, tag);
            this.filter();
        },
        pop(id) {
            Vue.delete(this.attached, id);
            this.filter();
        },
        filter() {
            this.table.query.tag = this.tagId;
            this.table.index();
        }
    },
    template: html
}