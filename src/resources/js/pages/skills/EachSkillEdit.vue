<template>
    <v-container>
        <v-form v-if="apiStatus !== null" ref="form" v-model="valid">
            <!-- 期間 -->
            <v-row dense>
                <v-col cols="12" class="mb-3">
                    <v-card max-height="520px" class="overflow-y-auto">
                        <v-list dense>
                            <v-list-item-group
                                v-model="activeSkill"
                                active-class="light-blue lighten-4"
                                v-scroll:#scroll-target="onScroll"
                                @change="setActiveSkill()"
                                mandatory
                            >
                                <p
                                    v-if="skills.length === 0"
                                    class="text-center text--secondary"
                                    v-text="'追加済みスキル無し'"
                                ></p>

                                <v-list-item :key="0">
                                    <v-list-item-content>
                                        <v-list-item-title>新しいスキルの追加</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item v-for="(skill, i) in sortSkills" :key="i+1">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ `${skill.name} - ${skill.type}` }}</v-list-item-title>
                                        <v-list-item-subtitle>
                                            <v-rating
                                                v-model.number="skill.level"
                                                :length="5"
                                                :half-increments="true"
                                                :readonly="true"
                                                :dense="true"
                                                small
                                                background-color="orange lighten-3"
                                                color="orange"
                                            ></v-rating>
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-icon>
                                        <v-btn
                                            depressed
                                            fab
                                            x-small
                                            color="error"
                                            @click="removeSkill(i)"
                                        >
                                            <v-icon>remove</v-icon>
                                        </v-btn>
                                    </v-list-item-icon>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                    </v-card>
                </v-col>

                <v-col cols="12">
                    <v-btn color="warning" rounded @click="addSkill()">
                        <v-icon left>{{ isNewSkill ? 'add' : 'edit' }}</v-icon>
                        {{ isNewSkill ? '追加' : '更新' }}
                    </v-btn>
                </v-col>

                <v-col cols="12">
                    <!-- スキル名 -->
                    <v-combobox
                        label="スキル名"
                        v-model="editForm.name"
                        :rules="rules.name"
                        :items="skillSamples['SKILL']"
                        counter="50"
                        hide-selected
                        tabindex="1"
                    ></v-combobox>

                    <!-- スキルタイプ -->
                    <v-combobox
                        label="スキルタイプ"
                        v-model="editForm.type"
                        :rules="rules.type"
                        :items="skillSamples['TYPE']"
                        counter="50"
                        hide-selected
                        tabindex="2"
                    ></v-combobox>

                    <!-- 習熟度 -->
                    <v-layout class="my-5" column>
                        <v-label>
                            <v-icon>mdi-arm-flex</v-icon>
                            <span>習熟度</span>
                        </v-label>
                        <v-rating
                            v-model.number="editForm.level"
                            :length="5"
                            :half-increments="true"
                            background-color="orange lighten-3"
                            color="orange"
                            clearable
                            hover
                            tabindex="3"
                        ></v-rating>
                        <span
                            class="caption"
                        >{{ nowLevelNote === null ? '' : `(目安) ★${nowLevelNote.level} : ${nowLevelNote.note}` }}</span>
                    </v-layout>
                </v-col>

                <v-col cols="12" sm="6" md="4" lg="3">
                    <!-- 経験期間 -->
                    <v-text-field
                        label="経験期間"
                        prepend-inner-icon="hourglass_empty"
                        v-model.number="editForm.experience_period"
                        :rules="rules.experience_period"
                        type="number"
                        min="0"
                        max="100"
                        suffix="年"
                        tabindex="4"
                    />
                </v-col>

                <v-col cols="12">
                    <!-- ツール -->
                    <v-combobox
                        label="ツール（任意）"
                        v-model="editForm.tool"
                        :rules="rules.tool"
                        :items="skillSamples['TOOL']"
                        multiple
                        chips
                        hide-selected
                        tabindex="5"
                    ></v-combobox>

                    <!-- プラットフォーム -->
                    <v-combobox
                        label="プラットフォーム（任意）"
                        v-model="editForm.platform"
                        :rules="rules.platform"
                        :items="skillSamples['PLATFORM']"
                        multiple
                        chips
                        hide-selected
                        tabindex="6"
                    ></v-combobox>

                    <!-- システム -->
                    <v-combobox
                        label="システム（任意）"
                        v-model="editForm.system"
                        :rules="rules.system"
                        :items="skillSamples['SYSTEM']"
                        multiple
                        chips
                        hide-selected
                        tabindex="7"
                    ></v-combobox>

                    <!-- 経験詳細 -->
                    <v-textarea
                        label="経験詳細（任意）"
                        prepend-inner-icon="comment"
                        v-model="editForm.experience_detail"
                        :rules="rules.experience_detail"
                        counter="1000"
                        auto-grow
                        tabindex="8"
                    />
                </v-col>

                <v-col cols="12">
                    <v-btn color="warning" rounded @click="addSkill()" tabindex="15">
                        <v-icon left>{{ isNewSkill ? 'add' : 'edit' }}</v-icon>
                        {{ isNewSkill ? '追加' : '更新' }}
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import utils from "../../mixins/util.js";
import { OK, CREATED, UNPROCESSABLE_ENTITY } from "../../util";

export default {
    name: "EachSkillEdit",
    props: ["value"],
    mixins: [utils],
    data() {
        return {
            scrollOption: {
                duration: 500
            },
            onScroll: null,
            activeSkill: 0,
            skills: [],
            editForm: {},
            valid: true,
            rules: {
                name: [
                    v => !!v || "スキル名は必須です。",
                    v =>
                        (v && v.length <= 50) ||
                        "スキル名は50文字以下で入力してください。"
                ],
                type: [
                    v => !!v || "スキルタイプは必須です。",
                    v =>
                        (v && v.length <= 50) ||
                        "スキルタイプは50文字以下で入力してください。"
                ],
                experience_period: [
                    v => !!v || "経験期間は必須です。",
                    v =>
                        (v && v >= 0 && v <= 100) ||
                        "経験期間は0~100の範囲で入力してください。"
                ],
                tool: [
                    v =>
                        (v && v.length <= 100) ||
                        "ツールは100個以下で入力してください。"
                ],
                platform: [
                    v =>
                        (v && v.length <= 100) ||
                        "プラットフォームは100個以下で入力してください。"
                ],
                system: [
                    v =>
                        (v && v.length <= 100) ||
                        "システムは100個以下で入力してください。"
                ],
                experience_detail: [
                    v =>
                        v === "" ||
                        v === null ||
                        v.length <= 1000 ||
                        "経験詳細は1000文字以下で入力してください。"
                ]
            }
        };
    },
    beforeCreate() {},
    created() {
        this.ref();
        this.setEditForm();
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        setEditForm(index = null) {
            if (index === null) {
                this.editForm = {
                    name: "",
                    type: "",
                    level: 0,
                    experience_period: null,
                    tool: [],
                    platform: [],
                    system: [],
                    experience_detail: ""
                };
            } else {
                for (let [key, value] of Object.entries(
                    this.skills[index - 1]
                )) {
                    this.editForm[key] = value;
                }
            }
        },
        async ref() {
            this.$store.commit("view/setApiStatus", null);
            const response = await axios.get(
                `/api/skills/${this.$route.params.id}/each-skills`
            );
            const skills = response.data || [];

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.skills = skills;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);
            // エラーメッセージ
            this.$toast.error("スキル情報を取得できませんでした");
        },
        async edit() {
            this.$emit("input", true);
            const response = await axios.post(
                `/api/skills/${this.$route.params.id}/each-skills`,
                { skill_list: this.skills },
                { headers: { "X-HTTP-Method-Override": "PUT" } }
            );
            const skills = response.data || [];
            if (response.status === CREATED) {
                this.$emit("input", false);
                this.skills = skills;

                // 更新成功メッセージ
                this.$toast.success("スキル情報を更新しました");

                return false;
            }
            this.$emit("input", false);
            this.$store.commit("error/setCode", response.status);
            if (response.status === UNPROCESSABLE_ENTITY) {
                // バリデーションエラー
                // 何もしない
            }

            // エラーメッセージ
            this.$toast.error("スキル情報を更新できませんでした");
        },
        addSkill() {
            if (!this.validate()) {
                // バリデーションエラー

                // エラーメッセージ
                this.$toast.error("入力エラーがあります");

                return false;
            }

            const skill = {};

            // コピー
            for (let [key, value] of Object.entries(this.editForm)) {
                skill[key] = value;
            }

            // 新規であれば追加、既存であれば更新
            if (this.isNewSkill) {
                this.skills.push(skill);
            } else {
                this.skills[this.activeSkill - 1] = skill;
            }
            this.resetValidation();
            this.setEditForm();

            // 成功メッセージ
            this.$toast.success(
                "スキルを" + (this.isNewSkill ? "追加" : "更新") + "しました。"
            );
            this.$vuetify.goTo(0, this.scrollOption);
            this.activeSkill = 0;
        },
        removeSkill(index) {
            this.activeSkill = 0;
            this.skills.splice(index, 1);

            // 成功メッセージ
            this.$toast.success("スキルを削除しました。");
        },
        validate() {
            return this.$refs.form.validate();
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        setActiveSkill() {
            if (this.isNewSkill) {
                this.setEditForm();
            } else {
                this.setEditForm(this.activeSkill);
            }
            this.resetValidation();
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        sortSkills() {
            return this.skills.sort((a, b) => (b.level > a.level ? 1 : -1));
        },
        isNewSkill() {
            return !this.activeSkill || this.activeSkill === 0;
        },
        skillSamples() {
            return this.$store.getters["constant/skill"];
        },
        nowLevelNote() {
            // 未選択の場合
            if (this.editForm.level < 1) {
                return null;
            }

            let result = {};
            for (let [key, value] of Object.entries(
                this.skillSamples["LEVEL_NOTE"]
            )) {
                if (
                    this.editForm.level === Number(key) ||
                    this.editForm.level === Number(key) + 0.5
                ) {
                    result = { level: key, note: value };
                }
            }

            return result;
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
