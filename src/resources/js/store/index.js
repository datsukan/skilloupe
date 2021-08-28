import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import error from "./error";
import view from "./view";
import constant from "./const";
import search from "./search";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        view,
        constant,
        search
    }
});

export default store;
