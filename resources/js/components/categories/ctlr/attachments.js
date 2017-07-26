import shared from 'belt/glue/js/components/categories/ctlr/shared';

// components
import attachments from 'belt/clip/js/components/clippables/ctlr/index';

export default {
    mixins: [shared],
    components: {
        tab: attachments,
    },
}