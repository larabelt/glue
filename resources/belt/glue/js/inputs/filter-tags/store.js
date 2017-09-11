import Table from 'belt/glue/js/tags/table';

export default {
    namespaced: true,
    state: {
        attached: {},
        detached: new Table(),
        tags: new Table(),
        needle: '',
        morphable_type: '',
        morphable_id: '',
    },
    mutations: {
        emptyDetached: (state) => {
            state.needle = '';
            state.detached.empty();
        },
        morphableType: (state, morphable_type) => {
            state.morphable_type = morphable_type;
            state.detached.morphable_type = morphable_type;
        },
        morphableId: (state, morphable_id) => {
            state.morphable_id = morphable_id;
            state.detached.morphable_id = morphable_id;
        },
        needle: (state, needle) => state.needle = needle,
        push: (state, tag) => {
            Vue.set(state.attached, tag.id, tag);
        },
        remove: (state, tag) => Vue.delete(state.attached, tag.id, tag),
        search: (state) => {
            state.detached.query.q = state.needle;
            state.detached.query.not_in = _.map(state.attached, 'id').join(",");
            state.detached.index();
        },
        queryToAttached: (state, query) => {
            state.tags.query.in = query;
            state.tags.index()
                .then(() => {
                    let tags = state.tags.items;
                    for (let i = 0; i < tags.length; i++) {
                        let tag = tags[i];
                        Vue.set(state.attached, tag.id, tag);
                    }
                });
        },
    },
    actions: {
        emptyDetached: (context) => context.commit('emptyDetached'),
        morphableType: (context, morphable_type) => context.commit('morphableType', morphable_type),
        morphableId: (context, morphable_id) => context.commit('morphableId', morphable_id),
        needle: (context, needle) => context.commit('needle', needle),
        push: (context, tag) => {
            context.commit('push', tag);
            context.commit('search');
        },
        remove: (context, tag) => context.commit('remove', tag),
        search: (context) => context.commit('search'),
        queryToAttached: (context, query) => {
            context.commit('queryToAttached', query);
        }
    },
    getters: {
        attached: state => {
            return state.attached;
        },
        detached: state => {
            return state.detached.items;
        },
        hasAttached: state => {
            return !_.isEmpty(state.attached);
        },
        hasDetached: state => {
            return state.detached.items.length;
        },
        needle: state => {
            return state.needle;
        },
        query: state => {
            return _.map(state.attached, 'id').join(",");
        },
    }
}