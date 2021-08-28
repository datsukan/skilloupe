import { OK } from "../util";

const state = {
    const: null
};

const getters = {
    db: state => (state.const ? state.const["DB"] : []),
    project: state => (state.const ? state.const["PROJECT"] : []),
    skill: state => (state.const ? state.const["SKILL"] : []),
    all: state => (state.const ? state.const : [])
};

const mutations = {
    setConst(state, constants) {
        state.const = constants;
    }
};

const actions = {
    // 定数取得
    async ref(context) {
        const response = await axios.get("/api/const");
        const constants = response.data || null;

        if (response.status === OK) {
            context.commit("setConst", constants);
            return true;
        }

        context.commit("error/setCode", response.status, { root: true });
        return false;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
