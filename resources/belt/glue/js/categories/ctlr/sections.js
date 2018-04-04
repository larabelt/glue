import shared from 'belt/glue/js/categories/ctlr/shared';

// components
import sections from 'belt/content/js/sectionables/router';

export default {
    mixins: [shared],
    components: {
        tab: sections,
    },
}