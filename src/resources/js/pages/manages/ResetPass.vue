<template>
    <v-container>
        <v-text-field
            label="パスワード"
            name="password"
            prepend-inner-icon="lock"
            :append-icon="isShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :type="isShowPassword ? 'text' : 'password'"
            :error-messages="resetPassErrors ? resetPassErrors.password : null"
            v-model="resetPassForm.password"
            @click:append="isShowPassword = !isShowPassword"
            tabindex="1"
        />

        <v-text-field
            label="パスワード再入力"
            name="password_confirmation"
            prepend-inner-icon="lock"
            :append-icon="isShowPasswordConfirmation ? 'mdi-eye' : 'mdi-eye-off'"
            :type="isShowPasswordConfirmation ? 'text' : 'password'"
            :error-messages="resetPassErrors ? resetPassErrors.password_confirmation : null"
            v-model="resetPassForm.password_confirmation"
            @click:append="isShowPasswordConfirmation = !isShowPasswordConfirmation"
            tabindex="2"
        />

        <v-btn
            class="mt-2"
            color="info"
            @click="resetPass()"
            :disabled="apiStatus === null"
            tabindex="3"
        >再設定</v-btn>
    </v-container>
</template>

<script>
export default {
    name: "ResetPass",
    props: ["value", "dialog"],
    data() {
        return {
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
        async resetPass() {
            // authストアのeditアクションを呼び出す
            await this.$store.dispatch("auth/resetPass", this.resetPassForm);

            if (this.apiStatus) {
                // 更新成功時
                this.clearError();
                this.clearResetPassForm();
                this.$toast.success("再設定しました");
                this.$emit("reload");
                this.$emit("dialog", false);
            } else {
                // エラーメッセージ
                this.$toast.error("再設定できませんでした");
            }
        },
        clearError() {
            this.$store.commit("auth/setResetPassErrorMessages", null);
        },
        clearResetPassForm() {
            const formValue = {
                id: this.resetPassForm.id,
                password: "",
                password_confirmation: ""
            };

            this.$emit("input", formValue);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        resetPassErrors() {
            return this.$store.state.auth.resetPassErrorMessages;
        },
        resetPassForm() {
            return this.value;
        }
    }
};
</script>

<style scoped>
</style>
