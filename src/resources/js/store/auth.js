import {
    OK,
    CREATED,
    NO_CONTENT,
    UNAUTHORIZED,
    UNPROCESSABLE_ENTITY
} from "../util";

const state = {
    user: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
    editErrorMessages: null,
    destroyErrorMessages: null,
    resetPassErrorMessages: null,
    forgotPassErrorMessages: null,
    forgotPassResetErrorMessages: null,
    changePassErrorMessages: null
};

const getters = {
    check: state => !!state.user,
    id: state => (state.user ? state.user.id : ""),
    username: state => (state.user ? state.user.name : ""),
    isReadonly: state => (state.user ? state.user.is_readonly : false),
    isManage: state => (state.user ? state.user.is_manage : false)
};

const mutations = {
    setUser(state, user) {
        state.user = user;
    },
    setLoginErrorMessages(state, messages) {
        state.loginErrorMessages = messages;
    },
    setRegisterErrorMessages(state, messages) {
        state.registerErrorMessages = messages;
    },
    setDestroyErrorMessages(state, messages) {
        state.destroyErrorMessages = messages;
    },
    setEditErrorMessages(state, messages) {
        state.editErrorMessages = messages;
    },
    setResetPassErrorMessages(state, messages) {
        state.resetPassErrorMessages = messages;
    },
    setForgotPassErrorMessages(state, messages) {
        state.forgotPassErrorMessages = messages;
    },
    setForgotPassResetErrorMessages(state, messages) {
        state.forgotPassResetErrorMessages = messages;
    },
    setChangePassErrorMessages(state, messages) {
        state.changePassErrorMessages = messages;
    }
};

const actions = {
    // ユーザー登録
    async register(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/users", data);

        if (response.status === CREATED) {
            context.commit("view/setApiStatus", true, { root: true });
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setRegisterErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ユーザー編集
    async edit(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/users/" + data.id, data, {
            headers: { "X-HTTP-Method-Override": "PUT" }
        });

        if (response.status === NO_CONTENT) {
            context.commit("view/setApiStatus", true, { root: true });
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setEditErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // パスワード変更（ユーザー本人の操作）
    async changePass(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/users/passwords", data, {
            headers: { "X-HTTP-Method-Override": "PUT" }
        });

        if (response.status === NO_CONTENT) {
            context.commit("view/setApiStatus", true, { root: true });
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setChangePassErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // パスワード再設定（管理ユーザーの操作）
    async resetPass(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post(
            "/api/users/" + data.id + "/passwords",
            data,
            {
                headers: { "X-HTTP-Method-Override": "PUT" }
            }
        );

        if (response.status === NO_CONTENT) {
            context.commit("view/setApiStatus", true, { root: true });
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setResetPassErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // パスワード再設定トークン発行
    async forgotPass(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/password/forgot", data);

        if (response.status === CREATED) {
            context.commit("view/setApiStatus", true, { root: true });
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (
            response.status === UNAUTHORIZED ||
            response.status === UNPROCESSABLE_ENTITY
        ) {
            context.commit("setForgotPassErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // トークンによるパスワード再設定
    async forgotPassReset(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/password/forgot/reset", data);

        if (response.status === CREATED) {
            context.commit("view/setNavbar", null, { root: true });
            context.commit("view/setApiStatus", true, { root: true });
            context.commit("setUser", response.data);
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (
            response.status === UNAUTHORIZED ||
            response.status === UNPROCESSABLE_ENTITY
        ) {
            context.commit(
                "setForgotPassResetErrorMessages",
                response.data.errors
            );
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ユーザー削除
    async destroy(context, id) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.delete("/api/users/" + id);

        if (response.status === OK) {
            context.commit("view/setApiStatus", true, { root: true });
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setDestroyErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ログイン
    async login(context, data) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/login", data);

        if (response.status === OK) {
            context.commit("view/setNavbar", null, { root: true });
            context.commit("view/setApiStatus", true, { root: true });
            context.commit("setUser", response.data);
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setLoginErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    // ログアウト
    async logout(context) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.post("/api/logout");

        if (response.status === OK) {
            context.commit("view/setApiStatus", true, { root: true });
            context.commit("setUser", null);
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        context.commit("error/setCode", response.status, { root: true });
    },

    // ログインユーザーチェック
    async currentUser(context) {
        context.commit("view/setApiStatus", null, { root: true });
        const response = await axios.get("/api/user");
        const user = response.data || null;

        if (response.status === OK) {
            context.commit("view/setApiStatus", true, { root: true });
            context.commit("setUser", user);
            return false;
        }

        context.commit("view/setApiStatus", false, { root: true });
        context.commit("error/setCode", response.status, { root: true });
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
