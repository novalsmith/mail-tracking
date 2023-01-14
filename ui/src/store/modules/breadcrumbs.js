// modules/breadcrums.js
const state = {
    breadcrumData: []
  }
export default { 
    state,
    mutations: {
        breadcrumData(state, breadcrumData){
        state.breadcrumData = breadcrumData;
    }
    },
    actions: {
        breadcrumData(context, breadcrumData){
        context.commit('breadcrumData',breadcrumData);
        }
    },
    getters:{
        breadcrumData: (state) => {
        return state.breadcrumData;
        }
    },
    modules: {
    
    }
}