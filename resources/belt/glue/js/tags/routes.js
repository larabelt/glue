import index from 'belt/glue/js/tags/ctlr/index';
import create from 'belt/glue/js/tags/ctlr/create';
import edit  from 'belt/glue/js/tags/ctlr/edit';

export default [
    {path: '/tags', component: index, canReuse: false, name: 'tags'},
    {path: '/tags/create', component: create, name: 'tags.create'},
    {path: '/tags/edit/:id', component: edit, name: 'tags.edit'},
]