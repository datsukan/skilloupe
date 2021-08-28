<template>
    <v-app>
        <Navbar v-if="isLogin" />
        <Appbar v-if="isLogin" />
        <v-content>
            <RouterView />
        </v-content>
    </v-app>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Appbar from "./components/Appbar.vue";
import { UNAUTHORIZED, INTERNAL_SERVER_ERROR } from "./util";

export default {
    components: {
        Navbar,
        Appbar
    },
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        errorCode() {
            return this.$store.state.error.code;
        }
    },
    watch: {
        errorCode: {
            handler(val) {
                if (val === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                } else if (val === UNAUTHORIZED) {
                    // ストアのuserをクリア
                    this.$store.commit("auth/setUser", null);
                    // ログイン画面へ
                    this.$router.push("/login");
                }
            },
            immediate: true
        },
        $route() {
            this.$store.commit("error/setCode", null);
        }
    }
};
</script>
