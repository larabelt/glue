import index from './ctlr/index';
import create from './ctlr/create';
import edit  from './ctlr/edit';
import attachments  from './ctlr/attachments';
import sections  from './ctlr/sections';

export default [
    {path: '/categories', component: index, canReuse: false, name: 'categories'},
    {path: '/categories/create', component: create, name: 'categories.create'},
    {path: '/categories/edit/:id', component: edit, name: 'categories.edit'},
    {path: '/categories/edit/:id/attachments', component: attachments, name: 'categories.attachments'},
    {path: '/categories/edit/:id/sections/:section?', component: sections, name: 'categories.sections'},
]