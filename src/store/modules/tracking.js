// modules/user.js
const state = {}
export default {
    state,
    mutations: {
        trackings(state, trackings) {
            state.trackings = trackings;
        }
    },
    actions: {
        trackings(context, trackings) {
            context.commit('trackings', trackings);
        }
    },
    getters: {
        trackings: (state) => {
            return state.trackings;
        }
    },
    modules: {

    }
}