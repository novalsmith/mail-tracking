// modules/user.js
const state = {}
export default {
    state,
    mutations: {
        access(state, access) {
            state.access = access;
        }
    },
    actions: {
        access(context, access) {
            context.commit('access', access);
        }
    },
    getters: {
        access: (state) => {
            return state.access;
        }
    },
    modules: {

    }
}