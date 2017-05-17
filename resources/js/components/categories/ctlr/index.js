import indexTable from './index-table';

// templates make a change
import heading_html from 'belt/core/js/templates/heading.html';

export default {
    props: {
        form: {default: null},
        full_admin: {default: true},
    },
    components: {
        heading: {template: heading_html},
        indexTable
    },
    template: `
        <div>
            <heading v-if="full_admin">
                <span slot="title">Category Manager</span>
            </heading>
            <section class="content">
                <index-table></index-table>
            </section>
        </div>
        `
}