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
                                :error-messages="forgotPassResetErrors ? forgotPassResetErrors.email : null"
                                v-model="forgotPassResetForm.email"
                                @keyup.enter="setPass()"
                                tabindex="1"
                            />

                            <v-text-field
                                label="パスワード"
                                name="password"
                                prepend-inner-icon="lock"
                                :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="isShowPassword ? 'text' : 'password'"
                                :error-messages="forgotPassResetErrors ? forgotPassResetErrors.password : null"
                                v-model="forgotPassResetForm.password"
                                @click:append="isShowPassword = !isShowPassword"
                                @keyup.enter="setPass()"
                                tabindex="2"
                            />

                            <v-text-field
                                label="パスワード再入力"
                                name="password_confirmation"
                                prepend-inner-icon="lock"
                                :append-icon="isShowPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="isShowPasswordConfirmation ? 'text' : 'password'"
                                :error-messages="forgotPassResetErrors ? forgotPassResetErrors.password_confirmation : null"
                                v-model="forgotPassResetForm.password_confirmation"
                                @click:append="isShowPasswordConfirmation = !isShowPasswordConfirmation"
                                @keyup.enter="setPass()"
                                tabindex="3"
                            />

                            <v-btn
                                block
                                class="mt-2"
                                color="info"
                                @click="setPass()"
                                :disabled="apiStatus === null"
                                tabindex="4"
                            >パスワードを再設定</v-btn>
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
    name: "ForgotPasswordReset",
    mixins: [utils],
    data() {
        return {
            forgotPassResetForm: {},
            isShowPassword: false,
            isShowPasswordConfirmation: false
        };
    },
    beforeCreate() {},
    created() {
        this.clearForgotPassResetForm();
        this.clearError();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async setPass() {
            // authストアのforgotPassResetアクションを呼び出す
            await this.$store.dispatch(
                "auth/forgotPassReset",
                this.forgotPassResetForm
            );

            if (this.apiStatus) {
                // 成功メッセージ
                this.$toast.success("パスワードを再設定しました");
                // トップページに移動する
                this.$router.push("/");
            } else {
                // エラーメッセージ
                this.$toast.error("パスワードを再設定できません");
            }
        },
        clearForgotPassResetForm() {
            this.forgotPassResetForm = {
                token: this.$route.params.token,
                email: "",
                password: "",
                password_confirmation: ""
            };
        },
        clearError() {
            this.$store.commit("auth/setForgotPassResetErrorMessages", null);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        forgotPassResetErrors() {
            return this.$store.state.auth.forgotPassResetErrorMessages;
        }
    }
};
</script>

<style scoped>
</style>
