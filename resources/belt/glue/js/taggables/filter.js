// helpers
import Table from 'belt/glue/js/tags/table';

// templates
import filter_html from 'belt/glue/js/taggables/templates/filter.html';

export default {
    props: ['table'],
    data() {
        return {
            detached: new Table({
                morphable_type: this.$parent.morphable_type,
                morphable_id: this.$parent.morphable_id,
            }),
            attached: {},
            asdf: [],
        }
    },
    computed: {
        tagId() {
            return _.map(this.attached, 'id').join(",");
        }
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
    template: filter_html
}