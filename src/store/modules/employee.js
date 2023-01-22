// modules/user.js
const state = {}
export default {
    state,
    mutations: {
        employee(state, employee) {
            state.employee = employee;
        }
    },
    actions: {
        employee(context, employee) {
            context.commit('employee', employee);
        }
    },
    getters: {
        employee: (state) => {
            return state.employee;
        }
    },
    modules: {

    }
}