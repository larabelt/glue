import taggableService from './service';
import taggableIndexTemplate from './templates/index';

export default {
    data() {
        return {
            taggable_type: this.$parent.morphable_type,
            taggable_id: this.$parent.morphable_id,
        }
    },
    components: {
        'taggable-index': {
            mixins: [taggableService],
            template: taggableIndexTemplate,
            data() {
                return {
                    taggable_type: this.$parent.taggable_type,
                    taggable_id: this.$parent.taggable_id,
                }
            },
            mounted() {
                this.paginate();
            },
        },
    },
    template: `
        <div>
            <taggable-index></taggable-index>
        </div>
        `
}