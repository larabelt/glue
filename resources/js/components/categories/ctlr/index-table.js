import Table from '../table';
import index_html from '../templates/index.html';

export default {
    props: {
        confirm_btn: {default: false},
        full_admin: {default: true},
    },
    data() {
        return {
            loading: false,
            table: new Table({router: this.$router}),
        }
    },
    methods: {
        parentCheck(category) {
            let output = `<b>${category.name}</b>`;

            if (category.hierarchy.length > 1) {
                output = '';
                category.hierarchy.forEach((item, index) => {
                    let name = `(${item.id}) ${item.name} > `;

                    if (index == category.hierarchy.length - 1) {
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
        this.loading = true;
        this.table.index()
            .then(() => {
                this.loading = false;
            });
    },
    template: index_html,
}