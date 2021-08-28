<template>
    <v-container>
        <v-form ref="form">
            <v-row dense>
                <v-col cols="12" md="6" lg="4">
                    <!-- プロフィールアイコン -->
                    <v-file-input
                        label="アイコン画像"
                        prepend-icon
                        prepend-inner-icon="mdi-image"
                        accept="image/*"
                        :loading="hasFileLoading"
                        :error-messages="editErrorMessages ? editErrorMessages.icon : null"
                        v-model="editForm.icon"
                        @change="onFileChange"
                        tabindex="1"
                    ></v-file-input>
                    <v-card>
                        <v-img
                            :src="iconPreview || '/api/icons/' + $route.params.id"
                            max-height="200"
                            contain
                        ></v-img>
                    </v-card>
                </v-col>

                <v-col cols="12">
                    <!-- 性別 -->
                    <v-layout class="my-5" column>
                        <v-label>
                            <v-icon>mdi-clock</v-icon>
                            <span>性別</span>
                        </v-label>
                        <v-btn-toggle
                            class="my-1"
                            v-model.number="editForm.sex"
                            dense
                            borderless
                            v-if="sexConst"
                        >
                            <v-btn
                                min-width="100"
                                active-class="info white--text"
                                :value="1"
                                tabindex="2"
                            >
                                <v-icon
                                    :color="
                                        editForm.sex === sexConst['MALE'] ? 'white' : ''
                                    "
                                    class="mr-1"
                                >mdi-face</v-icon>
                                <span>男性</span>
                            </v-btn>

                            <v-btn
                                min-width="100"
                                active-class="error white--text"
                                :value="2"
                                tabindex="3"
                            >
                                <v-icon
                                    :color="
                                        editForm.sex === sexConst['FEMALE'] ? 'white' : ''
                                    "
                                    class="mr-1"
                                >mdi-face-woman</v-icon>
                                <span>女性</span>
                            </v-btn>

                            <v-btn
                                min-width="100"
                                active-class="success white--text"
                                :value="3"
                                tabindex="4"
                            >
                                <span>その他</span>
                            </v-btn>
                        </v-btn-toggle>
                        <v-messages class="error--text" :value="sexErrorMsg"></v-messages>
                    </v-layout>
                </v-col>

                <v-col cols="12" sm="6" md="4" lg="3">
                    <!-- 年齢 -->
                    <v-text-field
                        label="年齢"
                        prepend-inner-icon="face"
                        v-model.number="editForm.age"
                        :error-messages="editErrorMessages ? editErrorMessages.age : null"
                        :rules="rules.age"
                        type="number"
                        min="1"
                        max="100"
                        tabindex="5"
                    />

                    <!-- 経験年数 -->
                    <v-text-field
                        label="経験年数"
                        prepend-inner-icon="mdi-arm-flex"
                        v-model.number="editForm.experience_period"
                        :error-messages="editErrorMessages ? editErrorMessages.experience_period : null"
                        :rules="rules.experience_period"
                        type="number"
                        min="1"
                        max="100"
                        tabindex="6"
                    />
                </v-col>

                <v-col cols="12">
                    <!-- 自己紹介 -->
                    <v-textarea
                        label="自己紹介（任意）"
                        prepend-inner-icon="comment"
                        v-model="editForm.self_introduction"
                        :error-messages="
                            editErrorMessages ? editErrorMessages.self_introduction : null
                        "
                        :rules="rules.self_introduction"
                        counter="500"
                        auto-grow
                        tabindex="7"
                    />
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY } from "../../util";

export default {
    name: "ProfileEdit",
    props: ["value"],
    data() {
        return {
            editForm: {
                icon: null,
                sex: 2,
                age: 1,
                experience_period: 0,
                self_introduction: ""
            },
            editErrorMessages: null,
            editValidationErrors: {},
            iconPreview: null,
            hasFileLoading: false,
            rules: {
                sex: [v => !!v || "性別は必須です。"],
                age: [
                    v => !!v || "年齢は必須です。",
                    v =>
                        (v && v >= 1 && v <= 100) ||
                        "年齢は1~100の範囲で入力してください。"
                ],
                self_introduction: [
                    v =>
                        !v ||
                        (v && v.length <= 500) ||
                        "自己紹介は500文字以下で入力してください。"
                ]
            }
        };
    },
    beforeCreate() {},
    created() {
        this.clearError();
        this.ref();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async ref() {
            this.$store.commit("view/setApiStatus", null);
            const response = await axios.get(
                "/api/skills/" + this.$route.params.id
            );
            const skillDetail = response.data || null;

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);

                // 登録データが存在する場合はセット
                if (skillDetail.profile) {
                    this.editForm = {
                        icon: null,
                        sex: skillDetail.profile.sex,
                        age: skillDetail.profile.age,
                        experience_period:
                            skillDetail.profile.experience_period,
                        self_introduction: skillDetail.profile.self_introduction
                    };
                }

                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);

            // エラーメッセージ
            this.$toast.error("プロファイル情報を取得できませんでした");
        },
        async edit() {
            // ファイルがあるのでJSONではなくFormDataにセット
            const formData = new FormData();
            for (let [key, value] of Object.entries(this.editForm)) {
                if (
                    !(key === "icon" && (value === null || value === undefined))
                ) {
                    formData.append(key, value);
                }
            }

            this.$emit("input", true);
            const response = await axios.post(
                "/api/skills/" + this.$route.params.id,
                formData,
                {
                    headers: { "X-HTTP-Method-Override": "PUT" }
                }
            );
            const skillDetail = response.data || null;

            if (response.status === CREATED || response.status === NO_CONTENT) {
                this.$emit("input", false);

                // 登録データが存在する場合はセット
                if (skillDetail) {
                    this.editForm = {
                        icon: null,
                        sex: skillDetail.sex,
                        age: skillDetail.age,
                        experience_period: skillDetail.experience_period,
                        self_introduction: skillDetail.self_introduction
                    };
                }

                // 更新成功メッセージ
                this.$toast.success("プロファイル情報を更新しました");

                return false;
            }

            this.$emit("input", false);
            if (response.status === UNPROCESSABLE_ENTITY) {
                this.editErrorMessages = response.data.errors;
            } else {
                this.$store.commit("error/setCode", response.status);
            }

            // エラーメッセージ
            this.$toast.error("プロファイル情報を更新できませんでした");
        },
        clearEditForm() {
            this.editForm = {
                icon: null,
                sex: 2,
                age: 1,
                experience_period: 0,
                self_introduction: ""
            };
            this.iconPreview = null;
        },
        onFileChange(event) {
            // 読み込みステータス=true
            this.hasFileLoading = true;

            // 何も選択されていなかったら処理中断
            if (event === undefined || event === null) {
                this.iconPreview = null;
                this.hasFileLoading = false;
                return false;
            }

            // ファイルが画像ではなかったら処理中断
            if (!event.type.match("image.*")) {
                this.$set(this.editForm, "icon", null);
                this.iconPreview = null;
                this.$toast.error("画像ファイル以外は指定できません");
                this.hasFileLoading = false;
                return false;
            }

            // FileReaderクラスのインスタンスを取得
            const reader = new FileReader();

            // ファイルを読み込み終わったタイミングで実行する処理
            reader.onload = e => {
                this.iconPreview = e.target.result;
                this.hasFileLoading = false;
            };

            // ファイルを読み込む
            reader.readAsDataURL(event);
        },
        clearError() {
            this.editErrorMessages = null;
        },
        validate() {
            let sexValidate = true;

            this.rules.sex.map(rule => {
                if (rule(this.editForm.sex) !== true) {
                    sexValidate = false;
                }
            });

            return this.$refs.form.validate() && sexValidate;
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        sexConst() {
            const dbConst = this.$store.getters["constant/db"];
            return dbConst["SEX"];
        },
        sexErrorMsg() {
            let messages = [];

            // フロントエンドバリデーション
            this.rules.sex.map(rule => {
                messages.push(rule(this.editForm.sex));
            });

            // バックエンドバリデーション
            if (this.editErrorMessages && this.editErrorMessages.sex) {
                this.editErrorMessages.sex.map(error => {
                    messages.push(error);
                });
            }

            return messages;
        }
    },
    watch: {
        $route(to, from) {
            this.ref();
        }
    }
};
</script>

<style scoped></style>
