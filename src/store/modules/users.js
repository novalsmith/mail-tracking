// modules/user.js
const state = {}
export default {
    state,
    mutations: {
        users(state, users) {
            state.users = users;
        }
    },
    actions: {
        users(context, users) {
            context.commit('users', users);
        }
    },
    getters: {
        users: (state) => {
            return state.users;
        }
    },
    modules: {

    }
}