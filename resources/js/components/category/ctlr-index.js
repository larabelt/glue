import headingTemplate from 'belt/core/js/templates/heading2.html';
import indexTemplate from './templates/index.html';
import CategoryTable from './table';

export default {

    components: {
        heading: {template: headingTemplate},
        categoryIndex: {
            data() {
                return {
                    table: new CategoryTable({router: this.$router}),
                }
            },
            mounted() {
                this.table.updateQueryFromRouter();
                this.table.index();
            },
            template: indexTemplate,
        },
    },

    template: `
        <div>
            <heading>
                <span slot="title">Category Manager</span>
            </heading>
            <section class="glue">
                <category-index></category-index>
            </section>
        </div>
        `
}