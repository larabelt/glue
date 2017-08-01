import shared from 'belt/glue/js/categories/ctlr/shared';

// components
import sections from 'belt/content/js/sectionables/ctlr/index';

export default {
    mixins: [shared],
    components: {
        tab: sections,
    },
}