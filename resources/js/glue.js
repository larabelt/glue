
import categories  from './components/categories/routes';
import tags  from './components/tags/routes';

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

            const app = new Vue({router}).$mount('#belt-glue');
        }
    }

}