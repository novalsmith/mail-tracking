const state = {}
export default {
    state,
    mutations: {
        unknowns(state, unknowns) {
            state.unknowns = unknowns;
        }
    },
    actions: {
        unknowns(context, unknowns) {
            context.commit('unknowns', unknowns);
        }
    },
    getters: {
        unknowns: (state) => {
            return state.unknowns;
        }
    },
    modules: {

    }
}