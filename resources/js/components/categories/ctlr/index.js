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

                    if( category.hierarchy.length > 1 ) {
                        output = '';
                        category.hierarchy.forEach((item, index) => {
                           let name = `(${item.id}) ${item.name} > `;

                           if( index == category.hierarchy.length - 1 ) {
                               name = `<b>${item.name}</b>`;
                           }
                            output = output + name;
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