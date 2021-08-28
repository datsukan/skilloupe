<template>
    <v-container>
        <v-text-field
            label="名前"
            name="username"
            prepend-inner-icon="person"
            type="username"
            :error-messages="editErrors ? editErrors.name : null"
            v-model="editForm.name"
            tabindex="1"
        />

        <v-text-field
            label="メールアドレス"
            name="email"
            prepend-inner-icon="email"
            type="email"
            :error-messages="editErrors ? editErrors.email : null"
            v-model="editForm.email"
            tabindex="2"
        />

        <v-switch
            :label="'参照専用 ' + (editForm && editForm.is_readonly ? 'ON' : 'OFF')"
            name="is_readonly"
            color="warning"
            :error-messages="editErrors ? editErrors.is_readonly : null"
            v-model="editForm.is_readonly"
            tabindex="3"
        ></v-switch>

        <v-switch
            :label="'管理権限 ' + (editForm && editForm.is_manage ? 'ON' : 'OFF')"
            name="is_manage"
            color="warning"
            :error-messages="editErrors ? editErrors.is_manage : null"
            v-model="editForm.is_manage"
            tabindex="4"
        ></v-switch>

        <v-btn
            class="mt-2"
            color="info"
            @click="edit()"
            :disabled="apiStatus === null"
            tabindex="4"
        >更新</v-btn>
    </v-container>
</template>

<script>
export default {
    name: "UserEdit",
    props: ["value", "dialog"],
    data() {
        return {};
    },
    beforeCreate() {},
    created() {},
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async edit() {
            // authストアのeditアクションを呼び出す
            await this.$store.dispatch("auth/edit", this.editForm);

            if (this.apiStatus) {
                // 更新成功時
                this.clearError();
                this.clearEditForm();
                this.$toast.success("更新しました");
                this.$emit("reload");
                this.$emit("dialog", false);
            } else {
                // エラーメッセージ
                this.$toast.error("更新できませんでした");
            }
        },
        clearError() {
            this.$store.commit("auth/setEditErrorMessages", null);
        },
        clearEditForm() {
            const formValue = {
                id: null,
                name: "",
                email: "",
                is_readonly: false,
                is_manage: false
            };

            this.$emit("input", formValue);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        editErrors() {
            return this.$store.state.auth.editErrorMessages;
        },
        editForm() {
            return this.value;
        }
    }
};
</script>

<style scoped></style>
