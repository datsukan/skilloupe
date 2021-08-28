const state = {
    navbar: null,
    apiStatus: null
};

const mutations = {
    setNavbar(state, flag) {
        state.navbar = flag;
    },
    setApiStatus(state, status) {
        state.apiStatus = status;
    }
};

export default {
    namespaced: true,
    state,
    mutations
};
