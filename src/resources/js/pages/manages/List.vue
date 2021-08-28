<template>
    <v-container>
        <v-text-field
            v-model="search"
            prepend-inner-icon="mdi-magnify"
            label="検索"
            clearable
            hide-details
        ></v-text-field>
        <v-data-table
            :headers="headers"
            :items="users"
            disable-pagination
            hide-default-footer
            :loading="apiStatus === null"
            loading-text="読み込み中..."
            no-data-text="データなし"
            no-results-text="該当なし"
            :search="search"
        >
            <template v-slot:item.is_readonly="{ item }">
                <v-chip
                    :color="item.is_readonly ? 'warning' : ''"
                    small
                >{{ item.is_readonly ? 'ON' : 'OFF' }}</v-chip>
            </template>

            <template v-slot:item.is_manage="{ item }">
                <v-chip
                    :color="item.is_manage ? 'warning' : ''"
                    small
                >{{ item.is_manage ? 'ON' : 'OFF' }}</v-chip>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon
                            v-on="on"
                            color="success"
                            class="mr-2"
                            @click="editUser(item)"
                        >mdi-pencil</v-icon>
                    </template>
                    <span>編集</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon
                            v-on="on"
                            color="info"
                            class="mr-2"
                            @click="resetPass(item)"
                        >mdi-lock-reset</v-icon>
                    </template>
                    <span>パスワード変更</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon
                            v-on="on"
                            color="error"
                            :disabled="item.id === id"
                            @click="confirmDeleteUser(item)"
                        >mdi-delete</v-icon>
                    </template>
                    <span>削除</span>
                </v-tooltip>
            </template>
        </v-data-table>

        <!-- スケルトンローダー -->
        <v-skeleton-loader
            v-if="apiStatus === null && users.length === 0"
            type="table-row-divider@15"
        ></v-skeleton-loader>

        <v-dialog v-model="editDialog" :max-width="dialogWidth">
            <v-card>
                <v-card-title>
                    <span class="headline">編集</span>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" color="info" text @click="editDialog = false" icon>
                                <v-icon large>mdi-close-circle</v-icon>
                            </v-btn>
                        </template>
                        <span>閉じる</span>
                    </v-tooltip>
                </v-card-title>

                <v-card-text>
                    <user-edit
                        v-model="editForm"
                        @dialog="editDialog = $event"
                        @reload="ref"
                        ref="userEdit"
                    ></user-edit>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="resetPassDialog" :max-width="dialogWidth">
            <v-card>
                <v-card-title>
                    <span class="headline">パスワード再設定</span>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                color="info"
                                text
                                @click="resetPassDialog = false"
                                icon
                            >
                                <v-icon large>mdi-close-circle</v-icon>
                            </v-btn>
                        </template>
                        <span>閉じる</span>
                    </v-tooltip>
                </v-card-title>

                <v-card-text>
                    <reset-pass
                        v-model="resetPassForm"
                        @dialog="resetPassDialog = $event"
                        @reload="ref"
                        ref="resetPass"
                    ></reset-pass>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="confirm" :max-width="confirmWidth">
            <v-card>
                <v-card-title>
                    <span class="headline">本当に削除しますか？</span>
                </v-card-title>

                <v-card-text>
                    <p v-text="deleteForm.name + '\n' + deleteForm.email"></p>
                </v-card-text>

                <v-card-actions>
                    <v-btn color="red" text @click="deleteUser()" :disabled="apiStatus === null">削除</v-btn>
                    <v-btn text @click="confirm = false" :disabled="apiStatus === null">キャンセル</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import utils from "../../mixins/util.js";
import { OK } from "../../util";
import UserEdit from "./Edit.vue";
import ResetPass from "./ResetPass.vue";

export default {
    name: "UserList",
    mixins: [utils],
    components: {
        UserEdit,
        ResetPass
    },
    data() {
        return {
            dialogWidth: 800,
            confirmWidth: 400,
            headers: [],
            users: [],
            search: "",
            editForm: {},
            editDialog: false,
            confirm: false,
            deleteForm: {},
            resetPassForm: {},
            resetPassDialog: false
        };
    },
    beforeCreate() {},
    created() {
        this.setHeaders();
        this.ref();
        this.clearDeleteForm();
        this.clearError();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        setHeaders() {
            this.headers = [
                { text: "id", value: "id", align: "center" },
                { text: "名前", value: "name", align: "center" },
                { text: "メールアドレス", value: "email", align: "center" },
                { text: "参照専用", value: "is_readonly", align: "center" },
                { text: "管理権限", value: "is_manage", align: "center" },
                {
                    text: "操作",
                    value: "actions",
                    align: "center",
                    sortable: false
                }
            ];
        },
        async ref() {
            this.$store.commit("view/setApiStatus", null);
            const response = await axios.get("/api/users", {
                headers: { Accept: "application/json" }
            });
            const users = response.data || null;

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.users = users;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);

            // エラーメッセージ
            this.$toast.error("ユーザー情報を取得できませんでした");
        },
        editUser(item) {
            this.editForm = {
                id: item.id,
                name: item.name,
                email: item.email,
                is_readonly: item.is_readonly,
                is_manage: item.is_manage
            };

            this.clearError();
            this.editDialog = true;
        },
        confirmDeleteUser(item) {
            this.deleteForm = {
                id: item.id,
                name: item.name,
                email: item.email
            };

            this.confirm = true;
        },
        async deleteUser() {
            this.clearError();

            // authストアのアクションを呼び出す
            await this.$store.dispatch("auth/destroy", this.deleteForm.id);

            if (this.apiStatus) {
                // 更新成功時
                this.clearError();
                this.clearDeleteForm();
                this.$toast.success("削除しました");
                this.ref();
                this.confirm = false;
            } else {
                // エラーメッセージ
                this.$toast.error("削除できませんでした");
            }
        },
        resetPass(item) {
            this.resetPassForm = {
                id: item.id,
                password: "",
                password_confirmation: ""
            };

            this.clearError();
            this.resetPassDialog = true;
        },
        clearDeleteForm() {
            this.deleteForm = {
                id: "",
                name: "",
                email: ""
            };
        },
        clearError() {
            this.$store.commit("auth/setEditErrorMessages", null);
            this.$store.commit("auth/setDestroyErrorMessages", null);
            this.$store.commit("auth/setResetPassErrorMessages", null);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        id() {
            return this.$store.getters["auth/id"];
        }
    }
};
</script>

<style scoped></style>
