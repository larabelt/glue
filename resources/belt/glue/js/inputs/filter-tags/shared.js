import base from 'belt/core/js/inputs/filter-base';
import store from 'belt/glue/js/inputs/filter-tags/store';

export default {
    mixins: [base],
    props: {
        _query: {
            default: null,
        },
    },
    computed: {
        attached() {
            return this.$store.getters['filterTags/attached'];
        },
        detached() {
            return this.$store.getters['filterTags/detached'];
        },
        hasAttached() {
            return this.$store.getters['filterTags/hasAttached'];
        },
        hasDetached() {
            return this.$store.getters['filterTags/hasDetached'];
        },
        needle() {
            return this.$store.getters['filterTags/needle'];
        },
        query() {
            return this.$store.getters['filterTags/query'];
        },
    },
    beforeCreate() {
        if (!this.$store.state.filterTags) {
            this.$store.registerModule('filterTags', store);
        }
    },
    created() {
        if (this._query) {
            this.$store.dispatch('filterTags/queryToAttached', this._query);
        }
    },
    mounted() {
        this.$store.dispatch('filterTags/morphableType', this.morphable_type);
    },
    methods: {
        emptyDetached() {
            this.$store.dispatch('filterTags/emptyDetached');
        },
        push(tag) {
            this.$store.dispatch('filterTags/push', tag);
            this.$emit('filter-tags-update', {tag: this.query});
        },
        remove(tag) {
            this.$store.dispatch('filterTags/remove', tag);
            this.$emit('filter-tags-update', {tag: this.query});
        },
        filter: _.debounce(function (query) {
            let needle = e.target.value;
            if (needle) {
                this.$store.dispatch('filterTags/needle', needle);
                this.$store.dispatch('filterTags/search');
            } else {
                this.emptyDetached();
            }
        }, 250),
    },
}