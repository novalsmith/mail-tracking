const state = {}
export default {
    state,
    mutations: {
        historys(state, historys) {
            state.historys = historys;
        }
    },
    actions: {
        historys(context, historys) {
            context.commit('historys', historys);
        }
    },
    getters: {
        historys: (state) => {
            return state.historys;
        }
    },
    modules: {

    }
}