import Form from 'belt/glue/js/categories/form';
import Table from 'belt/glue/js/categories/table';
import TreeForm from 'belt/glue/js/categories/tree';
import index_html from 'belt/glue/js/categories/templates/index.html';

export default {
    props: {
        confirm_btn: {default: false},
        full_admin: {default: true},
    },
    data() {
        return {
            loading: false,
            moving: new Form(),
            table: new Table({router: this.$router}),
        }
    },
    computed: {
        isMoving() {
            return this.moving.id;
        },
    },
    methods: {
        cancelMove() {
            this.moving.reset();
        },
        move(id, position) {
            return new Promise((resolve, reject) => {

                let tree = new TreeForm({category: this.moving});

                tree.setData({
                    neighbor_id: id,
                    move: position,
                });

                tree.submit()
                    .then(() => {
                        this.moving.reset();
                        this.table.index();
                        resolve();
                    })
                    .catch(() => {
                        reject();
                    });
            });
        },
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
        },
        setMoving(id) {
            if (!this.moving.id) {
                this.moving.show(id);
            } else {
                this.moving.reset();
            }
        },
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