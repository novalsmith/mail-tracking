// modules/user.js
const state = {}
export default {
    state,
    mutations: {
        unit(state, unit) {
            state.unit = unit;
        }
    },
    actions: {
        unit(context, unit) {
            context.commit('unit', unit);
        }
    },
    getters: {
        unit: (state) => {
            return state.unit;
        }
    },
    modules: {

    }
}