// modules/user.js
const state = {
    user: null
  }
export default { 
    state,
    mutations: {
        user(state, user){
        state.user = user;
    }
    },
    actions: {
        user(context, user){
        context.commit('user',user);
        }
    },
    getters:{
        user: (state) => {
        return state.user;
        }
    },
    modules: {
    
    }
}