import form from 'belt/core/js/mixins/base/forms';

export default {

    mixins: [form],

    data() {
        return {
            url: '/api/v1/taggables',
            taggable_type: '',
            taggable_id: null,
        }
    },
    computed: {
      fullUrl() {
          let url = this.url +
              '/' + this.taggable_type +
              '/' + this.taggable_id +
              '/';
          return url;
      }
    },
    methods: {
        paginate(query) {
            this.query = _.merge(this.query, query);
            this.query = _.merge(this.query, {not: 0});
            let url = this.fullUrl + '?' + $.param(this.query);
            this.$http.get(url).then(function (response) {
                this.attached = response.data.data;
                this.paginator = this.setPaginator(response);
            }, function (response) {

            });
        },
        paginateNot(query) {

            this.query = _.merge(this.query, query);
            this.query = _.merge(this.query, {not: 1});

            if (this.query.q.length == 0) {
                return this.detached = [];
            }

            let url = this.fullUrl + '?' + $.param(this.query);
            this.$http.get(url).then(function (response) {
                this.detached = response.data.data;
            }, function (response) {

            });
        },
        attach(id) {
            this.errors = {};
            this.$http.post(this.fullUrl, {id: id}).then((response) => {
                this.paginate();
                this.paginateNot();
            }, (response) => {
                if (response.status == 422) {
                    this.errors = response.data.message;
                }
            });
            this.saving = false;
        },
        detach(id) {
            this.$http.delete(this.fullUrl + id).then(function (response) {
                if (response.status == 204) {
                    this.paginate();
                }
            });
        }
    }
};