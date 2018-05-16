import indexTable from 'belt/glue/js/categories/ctlr/index-table';

// templates make a change


export default {
    props: {
        form: {default: null},
        full_admin: {default: true},
    },
    components: {

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