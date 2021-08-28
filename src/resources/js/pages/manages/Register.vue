<template>
    <v-container>
        <v-form>
            <v-row dense>
                <v-col cols="12" md="6" lg="4">
                    <v-text-field
                        label="名前"
                        name="username"
                        prepend-inner-icon="person"
                        type="username"
                        :error-messages="registerErrors ? registerErrors.name : null"
                        v-model="registerForm.name"
                        tabindex="1"
                    />

                    <v-text-field
                        label="メールアドレス"
                        name="email"
                        prepend-inner-icon="email"
                        type="email"
                        :error-messages="registerErrors ? registerErrors.email : null"
                        v-model="registerForm.email"
                        tabindex="2"
                    />

                    <v-text-field
                        label="パスワード"
                        name="password"
                        prepend-inner-icon="lock"
                        :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="isShowPassword ? 'text' : 'password'"
                        :error-messages="registerErrors ? registerErrors.password : null"
                        v-model="registerForm.password"
                        @click:append="isShowPassword = !isShowPassword"
                        tabindex="3"
                    />

                    <v-text-field
                        label="パスワード再入力"
                        name="password_confirmation"
                        prepend-inner-icon="lock"
                        :append-icon="isShowPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="isShowPasswordConfirmation ? 'text' : 'password'"
                        :error-messages="registerErrors ? registerErrors.password_confirmation : null"
                        v-model="registerForm.password_confirmation"
                        @click:append="isShowPasswordConfirmation = !isShowPasswordConfirmation"
                        tabindex="4"
                    />

                    <v-switch
                        :label="'参照専用 ' + (registerForm && registerForm.is_readonly ? 'ON' : 'OFF')"
                        name="is_readonly"
                        color="warning"
                        :error-messages="registerErrors ? registerErrors.is_readonly : null"
                        v-model="registerForm.is_readonly"
                        tabindex="5"
                    ></v-switch>

                    <v-switch
                        :label="'管理権限 ' + (registerForm && registerForm.is_manage ? 'ON' : 'OFF')"
                        name="is_manage"
                        color="warning"
                        :error-messages="registerErrors ? registerErrors.is_manage : null"
                        v-model="registerForm.is_manage"
                        tabindex="6"
                    ></v-switch>

                    <v-btn
                        class="mt-2"
                        color="primary"
                        @click="register()"
                        :disabled="apiStatus === null"
                        tabindex="7"
                    >登録</v-btn>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import utils from "../../mixins/util.js";

export default {
    name: "UserRegister",
    mixins: [utils],
    data() {
        return {
            registerForm: {},
            isShowPassword: false,
            isShowPasswordConfirmation: false
        };
    },
    beforeCreate() {},
    created() {
        this.clearRegisterForm();
        this.clearError();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async register() {
            // authストアのregisterアクションを呼び出す
            await this.$store.dispatch("auth/register", this.registerForm);

            if (this.apiStatus) {
                // 登録成功時
                this.clearError();
                this.clearRegisterForm();
                this.$toast.success("登録しました");
            } else {
                // エラーメッセージ
                this.$toast.error("登録できませんでした");
            }
        },
        clearError() {
            this.$store.commit("auth/setRegisterErrorMessages", null);
        },
        clearRegisterForm() {
            this.registerForm = {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                is_readonly: false,
                is_manage: false
            };
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        registerErrors() {
            return this.$store.state.auth.registerErrorMessages;
        }
    }
};
</script>

<style scoped>
</style>
