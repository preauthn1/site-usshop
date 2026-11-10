export default {
    state: {
        siderCollapsed:false
    },
    mutations: {
        setSiderCollapsed(state){
            state.siderCollapsed = !state.siderCollapsed
        }
    },
    getters: {
        siderCollapsed: state => state.siderCollapsed
    },
    actions: {
        toggleSider({ commit }) {
            commit('setSiderCollapsed')
        }
    }
}
