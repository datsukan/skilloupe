<template>
    <v-container>
        <v-form>
            <v-row dense>
                <v-col cols="12" md="6" lg="4">
                    <v-text-field
                        label="現在のパスワード"
                        name="current_password"
                        prepend-inner-icon="lock"
                        :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="isShowPassword ? 'text' : 'password'"
                        :error-messages="changePassErrors ? changePassErrors.current_password : null"
                        v-model="changePassForm.current_password"
                        @click:append="isShowPassword = !isShowPassword"
                        tabindex="1"
                    />

                    <v-text-field
                        label="新しいパスワード"
                        name="password"
                        prepend-inner-icon="lock"
                        :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="isShowPassword ? 'text' : 'password'"
                        :error-messages="changePassErrors ? changePassErrors.password : null"
                        v-model="changePassForm.password"
                        @click:append="isShowPassword = !isShowPassword"
                        tabindex="1"
                    />

                    <v-text-field
                        label="新しいパスワードを再入力"
                        name="password_confirmation"
                        prepend-inner-icon="lock"
                        :append-icon="isShowPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="isShowPasswordConfirmation ? 'text' : 'password'"
                        :error-messages="changePassErrors ? changePassErrors.password_confirmation : null"
                        v-model="changePassForm.password_confirmation"
                        @click:append="isShowPasswordConfirmation = !isShowPasswordConfirmation"
                        tabindex="2"
                    />

                    <v-btn
                        class="mt-2"
                        color="info"
                        @click="changePass()"
                        :disabled="apiStatus === null"
                        tabindex="3"
                    >変更</v-btn>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
export default {
    name: "PasswordChange",
    data() {
        return {
            changePassForm: {},
            isShowPassword: false,
            isShowPasswordConfirmation: false
        };
    },
    beforeCreate() {},
    created() {},
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async changePass() {
            // authストアのeditアクションを呼び出す
            await this.$store.dispatch("auth/changePass", this.changePassForm);

            if (this.apiStatus) {
                // 更新成功時
                this.clearError();
                this.clearChangePassForm();
                this.$toast.success("変更しました");
            } else {
                // エラーメッセージ
                this.$toast.error("変更できませんでした");
            }
        },
        clearError() {
            this.$store.commit("auth/setChangePassErrorMessages", null);
        },
        clearChangePassForm() {
            this.changePassForm = {
                current_password: "",
                password: "",
                password_confirmation: ""
            };
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        changePassErrors() {
            return this.$store.state.auth.changePassErrorMessages;
        }
    }
};
</script>

<style scoped>
</style>
