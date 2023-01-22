// modules/user.js
const state = {}
export default {
    state,
    mutations: {
        inboxs(state, inboxs) {
            state.inboxs = inboxs;
        }
    },
    actions: {
        inboxs(context, inboxs) {
            context.commit('inboxs', inboxs);
        }
    },
    getters: {
        inboxs: (state) => {
            return state.inboxs;
        }
    },
    modules: {

    }
}