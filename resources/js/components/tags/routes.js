import index from './ctlr/index';
import create from './ctlr/create';
import edit  from './ctlr/edit';

export default [
    {path: '/tags', component: index, canReuse: false, name: 'tags'},
    {path: '/tags/create', component: create, name: 'tags.create'},
    {path: '/tags/edit/:id', component: edit, name: 'tags.edit'},
]