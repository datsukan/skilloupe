const state = {
    allSearch: "",
    customSearch: null
};

const mutations = {
    setAllSearch(state, word) {
        state.allSearch = word;
    },
    setCustomSearch(state, conditions) {
        state.customSearch = conditions;
    }
};

export default {
    namespaced: true,
    state,
    mutations
};
