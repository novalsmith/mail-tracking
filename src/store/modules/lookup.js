const state = {}
export default {
    state,
    mutations: {
        lookups(state, lookups) {
            state.lookups = lookups;
        }
    },
    actions: {
        lookups(context, lookups) {
            context.commit('lookups', lookups);
        }
    },
    getters: {
        lookups: (state) => {
            return state.lookups;
        }
    },
    modules: {

    }
}