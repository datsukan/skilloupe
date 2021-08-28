<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center" style="max-width: initial">
            <v-col cols="12" sm="8" md="6" lg="4" xl="3">
                <v-card class="elevation-12">
                    <v-progress-linear
                        :indeterminate="apiStatus === null"
                        :active="apiStatus === null"
                        color="primary"
                        height="5"
                        absolute
                        top
                    ></v-progress-linear>
                    <v-card-text>
                        <v-card-title class="justify-center">
                            <div>
                                <v-layout class="d-flex align-center">
                                    <v-img
                                        src="/image/icon_primary.png"
                                        max-height="36"
                                        max-width="36"
                                        class="mx-2"
                                        contain
                                    ></v-img>
                                    <h1 class="headline primary--text">Skilloupe</h1>
                                </v-layout>
                            </div>
                        </v-card-title>
                        <v-form>
                            <v-text-field
                                label="メールアドレス"
                                name="email"
                                prepend-inner-icon="email"
                                type="email"
                                :error-messages="loginErrors ? loginErrors.email : null"
                                v-model="loginForm.email"
                                @keyup.enter="login()"
                                tabindex="1"
                            />

                            <v-text-field
                                id="password"
                                label="パスワード"
                                name="password"
                                prepend-inner-icon="lock"
                                :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="isShowPassword ? 'text' : 'password'"
                                :error-messages="loginErrors ? loginErrors.password : null"
                                v-model="loginForm.password"
                                @click:append="isShowPassword = !isShowPassword"
                                @keyup.enter="login()"
                                tabindex="2"
                            />

                            <v-btn
                                block
                                class="mt-2"
                                color="primary"
                                @click="login()"
                                :disabled="apiStatus === null"
                                tabindex="3"
                            >ログイン</v-btn>

                            <v-checkbox
                                label="ログイン状態を保持する"
                                name="remember"
                                :error-messages="loginErrors ? loginErrors.remember : null"
                                v-model="loginForm.remember"
                                tabindex="4"
                            ></v-checkbox>

                            <p class="text-center">
                                <router-link to="/password/forgot">パスワードをお忘れの場合</router-link>
                            </p>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import utils from "./../mixins/util.js";

export default {
    name: "Login",
    mixins: [utils],
    data() {
        return {
            loginForm: {},
            isShowPassword: false
        };
    },
    beforeCreate() {},
    created() {
        this.clearLoginForm();
        this.clearError();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async login() {
            // authストアのloginアクションを呼び出す
            await this.$store.dispatch("auth/login", this.loginForm);

            if (this.apiStatus) {
                // トップページに移動する
                this.$router.push("/");
            } else {
                // エラーメッセージ
                this.$toast.error("ログインできませんでした");
            }
        },
        clearLoginForm() {
            this.loginForm = {
                email: "",
                password: "",
                remember: false
            };
        },
        clearError() {
            this.$store.commit("auth/setLoginErrorMessages", null);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        loginErrors() {
            return this.$store.state.auth.loginErrorMessages;
        }
    }
};
</script>

<style scoped>
</style>
