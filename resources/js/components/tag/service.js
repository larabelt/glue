import form from 'belt/core/js/mixins/base/forms';

export default {

    mixins: [form],

    data() {
        return {
            url: '/api/v1/tags/',
        }
    },

    methods: {
        paginate(query) {
            this.query = _.merge(this.query, query);
            let url = this.url + '?' + $.param(this.query);
            this.$http.get(url).then(function (response) {
                this.items = response.data.data;
                this.paginator = this.setPaginator(response);
            }, function (response) {

            });
        },
        get() {
            this.$http.get(this.url + this.item.id).then((response) => {
                this.item = response.data;
            }, (response) => {

            });
        },
        update(params) {
            this.errors = {};
            this.$http.put(this.url + this.item.id, params).then((response) => {
                this.item = response.data;
                this.saved = true;
            }, (response) => {
                if (response.status == 422) {
                    this.errors = response.data.message;
                }
            });
            this.saving = false;
        },
        store(params) {
            this.errors = {};
            this.$http.post(this.url, params).then((response) => {
                this.$router.push({name: 'tagEdit', params: {id: response.data.id}})
            }, (response) => {
                if (response.status == 422) {
                    this.errors = response.data.message;
                }
            });
            this.saving = false;
        },
        destroy(id) {
            this.$http.delete(this.url + id).then(function (response) {
                if (response.status == 204) {
                    this.paginate();
                }
            });
        }
    }
};