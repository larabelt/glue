// helpers
import Table from '../table';

// templates make a change
import heading_html from 'belt/core/js/templates/heading.html';
import index_html from '../templates/index.html';

export default {

    components: {
        heading: {template: heading_html},
        index: {
            data() {
                return {
                    table: new Table({router: this.$router}),
                }
            },
            methods: {
                parentCheck(category) {
                    let output = `<b>${category.name}</b>`;

                    if( category.id != null ) {
                        _.each(this.table.items, item => {
                            if (item.id == category.parent_id) {
                                output = `(${item.id})${item.name} > <b>${category.name} </b>`;
                            }
                        });
                    }
                    
                    return output;
                }
            },
            mounted() {
                this.table.updateQueryFromRouter();
                this.table.index();
            },
            template: index_html,
        },
    },

    template: `
        <div>
            <heading>
                <span slot="title">Category Manager</span>
            </heading>
            <section class="content">
                <index></index>
            </section>
        </div>
        `
}