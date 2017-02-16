import headingTemplate from 'belt/core/js/templates/base/heading.html';
import tagService from './service';
import tagFormTemplate from './templates/form';

export default {
    components: {
        'heading': {
            data() {
                return {
                    title: 'Tag Creator',
                    subtitle: '',
                    crumbs: [
                        {route: 'tagIndex', text: 'Tags'}
                    ],
                }
            },
            'template': headingTemplate
        },
        'tag-form': {
            mixins: [tagService],
            template: tagFormTemplate,
        },
    },
    template: `
        <div>
            <heading></heading>
            <section class="glue">
                <div class="box">
                    <div class="box-body">
                        <tag-form></tag-form>
                    </div>
                </div>
            </section>
        </div>
        `
}