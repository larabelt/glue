import index from 'belt/glue/js/categories/ctlr/index';
import create from 'belt/glue/js/categories/ctlr/create';
import edit  from 'belt/glue/js/categories/ctlr/edit';
import attachments  from 'belt/glue/js/categories/ctlr/attachments';
import sections  from 'belt/glue/js/categories/ctlr/sections';

export default [
    {path: '/categories', component: index, canReuse: false, name: 'categories'},
    {path: '/categories/create', component: create, name: 'categories.create'},
    {path: '/categories/edit/:id', component: edit, name: 'categories.edit'},
    {path: '/categories/edit/:id/attachments', component: attachments, name: 'categories.attachments'},
    {path: '/categories/edit/:id/sections/:section?', component: sections, name: 'categories.sections'},
]