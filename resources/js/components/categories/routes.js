import index from './ctlr/index';
import create from './ctlr/create';
import edit  from './ctlr/edit';
import sections  from './ctlr/sections';

export default [
    {path: '/categories', component: index, canReuse: false, name: 'categories'},
    {path: '/categories/create', component: create, name: 'categories.create'},
    {path: '/categories/edit/:id', component: edit, name: 'categories.edit'},
    {path: '/categories/edit/:id/sections', component: sections, name: 'categories.sections'},
]