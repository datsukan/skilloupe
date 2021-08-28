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
                        <v-form @submit.prevent>
                            <v-text-field
                                label="メールアドレス"
                                name="email"
                                prepend-inner-icon="email"
                                type="email"
                                :error-messages="forgotPassErrors ? forgotPassErrors.email : null"
                                v-model="forgotPassForm.email"
                                @keyup.enter="sendToken()"
                                tabindex="1"
                            />
                            <v-btn
                                block
                                class="mt-2"
                                color="primary"
                                @click="sendToken()"
                                :disabled="apiStatus === null"
                                tabindex="2"
                            >再設定メールを送信</v-btn>
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
    name: "ForgotPassword",
    mixins: [utils],
    data() {
        return {
            forgotPassForm: {}
        };
    },
    beforeCreate() {},
    created() {
        this.clearForgotPassForm();
        this.clearError();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async sendToken() {
            // authストアのforgotPassアクションを呼び出す
            await this.$store.dispatch("auth/forgotPass", this.forgotPassForm);

            if (this.apiStatus) {
                // 成功メッセージ
                this.$toast.success("リセットリンクを送信しました");
                // ログインページに移動する
                this.$router.push("/login");
            } else {
                // エラーメッセージ
                this.$toast.error("リセットリンクを送信できません");
            }
        },
        clearForgotPassForm() {
            this.forgotPassForm = {
                email: ""
            };
        },
        clearError() {
            this.$store.commit("auth/setForgotPassErrorMessages", null);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        forgotPassErrors() {
            return this.$store.state.auth.forgotPassErrorMessages;
        }
    }
};
</script>

<style scoped>
</style>
