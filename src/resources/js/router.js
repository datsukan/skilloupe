import Vue from "vue";
import VueRouter from "vue-router";

// ページコンポーネントをインポートする
import Dashboard from "./pages/Dashboard.vue";
import Login from "./pages/Login.vue";
import ForgotPassword from "./pages/ForgotPassword.vue";
import ForgotPasswordReset from "./pages/ForgotPasswordReset.vue";
import SkillDetail from "./pages/skills/Detail.vue";
import SkillEdit from "./pages/skills/Edit.vue";
import PasswordChange from "./pages/settings/PasswordChange.vue";
import UserRegister from "./pages/manages/Register.vue";
import UserList from "./pages/manages/List.vue";
import SystemError from "./pages/errors/System.vue";

import store from "./store";

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter);

// パスとコンポーネントのマッピング
const routes = [
    {
        path: "/",
        component: Dashboard
    },
    {
        path: "/login",
        component: Login,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/password/forgot",
        component: ForgotPassword,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/password/forgot/reset/:token",
        component: ForgotPasswordReset,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/skill/:id",
        component: SkillDetail
    },
    {
        path: "/skill/edit/:id",
        component: SkillEdit,
        beforeEnter(to, from, next) {
            if (
                !store.getters["auth/isManage"] &&
                to.params.id != store.getters["auth/id"]
            ) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/setting/password/change",
        component: PasswordChange
    },
    {
        path: "/manage/register",
        component: UserRegister,
        beforeEnter(to, from, next) {
            if (!store.getters["auth/isManage"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/manage/list",
        component: UserList,
        beforeEnter(to, from, next) {
            if (!store.getters["auth/isManage"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/500",
        component: SystemError
    }
];

// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: "history",
    routes
});

// ログインしていない場合はログイン画面へ
router.beforeEach((to, from, next) => {
    if (
        to.path !== "/login" &&
        to.path !== "/password/forgot" &&
        to.path !== `/password/forgot/reset/${to.params.token}` &&
        !store.getters["auth/check"]
    ) {
        next("/login");
    } else {
        next();
    }
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router;
