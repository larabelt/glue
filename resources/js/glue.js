import categoryIndex from './components/category/ctlr-index';
import categoryCreate from './components/category/ctlr-create';
import categoryEdit  from './components/category/ctlr-edit';
import tagIndex from './components/tag/ctlr-index';
import tagCreate from './components/tag/ctlr-create';
import tagEdit  from './components/tag/ctlr-edit';

export default class BeltGlue {

    constructor() {

        if ($('#belt-glue').length > 0) {

            const router = new VueRouter({
                mode: 'history',
                base: '/admin/belt/glue',
                routes: [
                    {path: '/categories', component: categoryIndex, canReuse: false, name: 'categoryIndex'},
                    {path: '/categories/create', component: categoryCreate, name: 'categoryCreate'},
                    {path: '/categories/edit/:id', component: categoryEdit, name: 'categoryEdit'},
                    {path: '/tags', component: tagIndex, canReuse: false, name: 'tagIndex'},
                    {path: '/tags/create', component: tagCreate, name: 'tagCreate'},
                    {path: '/tags/edit/:id', component: tagEdit, name: 'tagEdit'},
                ]
            });

            const app = new Vue({router}).$mount('#belt-glue');
        }
    }

}