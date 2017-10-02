import categories  from 'belt/glue/js/categories/routes';
import tags  from 'belt/glue/js/tags/routes';
import store from 'belt/core/js/store/index';

export default class BeltGlue {

    constructor() {

        if ($('#belt-glue').length > 0) {

            const router = new VueRouter({
                mode: 'history',
                base: '/admin/belt/glue',
                routes: []
            });

            router.addRoutes(categories);
            router.addRoutes(tags);

            const app = new Vue({router, store}).$mount('#belt-glue');
        }
    }

}