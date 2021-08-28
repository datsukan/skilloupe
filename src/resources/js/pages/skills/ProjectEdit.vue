<template>
    <v-container>
        <v-form v-if="apiStatus !== null" ref="form" v-model="valid">
            <!-- 期間 -->
            <v-row dense>
                <v-col cols="12" class="mb-3">
                    <v-card max-height="520px" class="overflow-y-auto">
                        <v-list dense>
                            <v-list-item-group
                                v-model="activeProject"
                                active-class="light-blue lighten-4"
                                v-scroll:#scroll-target="onScroll"
                                @change="setActiveProject()"
                                mandatory
                            >
                                <p
                                    v-if="projects.length === 0"
                                    class="text-center text--secondary"
                                    v-text="'追加済みプロジェクト無し'"
                                ></p>

                                <v-list-item :key="0">
                                    <v-list-item-content>
                                        <v-list-item-title>新しいプロジェクトの追加</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item v-for="(project, i) in sortProjects" :key="i+1">
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{
                                            project.title
                                            }}
                                        </v-list-item-title>
                                        <v-list-item-subtitle>
                                            {{
                                            project.started_at +
                                            " ~ " +
                                            project.ended_at +
                                            ` (${calculatePeriodAll[i]})`
                                            }}
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-icon>
                                        <v-btn
                                            depressed
                                            fab
                                            x-small
                                            color="error"
                                            @click="removeProject(i)"
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
                    <v-btn color="warning" rounded @click="addProject()">
                        <v-icon left>{{ isNewProject ? 'add' : 'edit' }}</v-icon>
                        {{ isNewProject ? '追加' : '更新' }}
                    </v-btn>
                </v-col>

                <v-col cols="12">
                    <!-- プロジェクト名 -->
                    <v-text-field
                        label="プロジェクト名"
                        v-model="editForm.title"
                        :rules="rules.title"
                        counter="50"
                        tabindex="1"
                    />
                </v-col>

                <v-col cols="12" sm="6" md="4" lg="3">
                    <!-- 期間：開始年月 -->
                    <v-menu
                        ref="startedAtMenu"
                        v-model="isActiveStartedAtPicker"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="editForm.started_at"
                                :rules="rules.started_at"
                                label="開始年月"
                                prepend-inner-icon="event"
                                readonly
                                v-on="on"
                                @keyup="isActiveStartedAtPicker = true"
                                @keydown="isActiveStartedAtPicker = false"
                                tabindex="2"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            ref="startedAtPicker"
                            v-model="editForm.started_at"
                            type="month"
                            locale="ja"
                            :max="
                                editForm.ended_at
                                    ? editForm.ended_at
                                    : new Date().toISOString().substr(0, 10)
                            "
                            min="1950-01-01"
                            @change="saveStartedAt"
                        ></v-date-picker>
                    </v-menu>

                    <!-- 期間：終了年月 -->
                    <v-menu
                        ref="endedAtMenu"
                        v-model="isActiveEndedAtPicker"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="editForm.ended_at"
                                :rules="rules.ended_at"
                                label="終了年月"
                                prepend-inner-icon="event"
                                readonly
                                v-on="on"
                                @keyup="isActiveEndedAtPicker = true"
                                @keydown="isActiveEndedAtPicker = false"
                                tabindex="3"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            ref="endedAtPicker"
                            v-model="editForm.ended_at"
                            type="month"
                            locale="ja"
                            :max="new Date().toISOString().substr(0, 10)"
                            :min="
                                editForm.started_at
                                    ? editForm.started_at
                                    : '1950-01-01'
                            "
                            @change="saveEndedAt"
                        ></v-date-picker>
                    </v-menu>
                </v-col>

                <v-col cols="12">
                    <!-- 概要 -->
                    <v-textarea
                        label="概要"
                        prepend-inner-icon="info"
                        v-model="editForm.summary"
                        :rules="rules.summary"
                        counter="500"
                        auto-grow
                        tabindex="4"
                    />
                </v-col>

                <v-col cols="12" sm="6" md="4" lg="3">
                    <!-- 人数 -->
                    <v-text-field
                        label="人数"
                        prepend-inner-icon="people"
                        v-model.number="editForm.member"
                        :rules="rules.member"
                        type="number"
                        min="1"
                        max="999999"
                        suffix="人"
                        tabindex="5"
                    />
                </v-col>

                <v-col cols="12">
                    <!-- システム -->
                    <v-combobox
                        label="システム（任意）"
                        v-model="editForm.system"
                        :rules="rules.system"
                        :items="projectSamples['SYSTEM']"
                        multiple
                        chips
                        hide-selected
                        tabindex="6"
                    ></v-combobox>

                    <!-- 領域 -->
                    <v-combobox
                        label="領域（任意）"
                        v-model="editForm.region"
                        :rules="rules.region"
                        :items="projectSamples['REGION']"
                        multiple
                        chips
                        hide-selected
                        tabindex="7"
                    ></v-combobox>

                    <!-- 役割 -->
                    <v-combobox
                        label="役割"
                        v-model="editForm.role"
                        :rules="rules.role"
                        :items="projectSamples['ROLE']"
                        multiple
                        chips
                        hide-selected
                        tabindex="8"
                    ></v-combobox>

                    <!-- 開発手法 -->
                    <v-combobox
                        label="開発手法（任意）"
                        v-model="editForm.dev_method"
                        :rules="rules.dev_method"
                        :items="projectSamples['DEV_METHOD']"
                        multiple
                        chips
                        hide-selected
                        tabindex="9"
                    ></v-combobox>

                    <!-- 工程 -->
                    <v-combobox
                        label="工程"
                        v-model="editForm.process"
                        :rules="rules.process"
                        :items="projectSamples['PROCESS']"
                        multiple
                        chips
                        hide-selected
                        tabindex="10"
                    ></v-combobox>

                    <!-- 言語・フレームワーク -->
                    <v-combobox
                        label="言語・フレームワーク（任意）"
                        v-model="editForm.lang_and_fw"
                        :rules="rules.lang_and_fw"
                        :items="projectSamples['LANG_AND_FW']"
                        multiple
                        chips
                        hide-selected
                        tabindex="11"
                    ></v-combobox>

                    <!-- OS・MW -->
                    <v-combobox
                        label="OS・MW（任意）"
                        v-model="editForm.os_and_mw"
                        :rules="rules.os_and_mw"
                        :items="projectSamples['OS_AND_MW']"
                        multiple
                        chips
                        hide-selected
                        tabindex="12"
                    ></v-combobox>

                    <!-- ツール -->
                    <v-combobox
                        label="ツール（任意）"
                        v-model="editForm.tool"
                        :rules="rules.tool"
                        :items="projectSamples['TOOL']"
                        multiple
                        chips
                        hide-selected
                        tabindex="13"
                    ></v-combobox>

                    <!-- 経験詳細 -->
                    <v-textarea
                        label="経験詳細"
                        prepend-inner-icon="comment"
                        v-model="editForm.experience_detail"
                        :rules="rules.experience_detail"
                        counter="1000"
                        auto-grow
                        tabindex="14"
                    />
                </v-col>

                <v-col cols="12">
                    <v-btn color="warning" rounded @click="addProject()" tabindex="15">
                        <v-icon left>{{ isNewProject ? 'add' : 'edit' }}</v-icon>
                        {{ isNewProject ? '追加' : '更新' }}
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
    name: "ProjectEdit",
    props: ["value"],
    mixins: [utils],
    data() {
        return {
            scrollOption: {
                duration: 500
            },
            onScroll: null,
            activeProject: 0,
            projects: [],
            editForm: {},
            valid: true,
            rules: {
                title: [
                    v => !!v || "プロジェクト名は必須です。",
                    v =>
                        (v && v.length <= 50) ||
                        "プロジェクト名は50文字以下で入力してください。"
                ],
                started_at: [
                    v => !!v || "開始日は必須です。",
                    v =>
                        (v && this.$moment(v).isValid()) ||
                        "開始日は年月（YYYY-MM）で指定してください。"
                ],
                ended_at: [
                    v => !!v || "終了日は必須です。",
                    v =>
                        (v && this.$moment(v).isValid()) ||
                        "終了日は年月（YYYY-MM）で指定してください。"
                ],
                summary: [
                    v => !!v || "概要は必須です。",
                    v =>
                        (v && v.length <= 500) ||
                        "概要は500文字以下で入力してください。"
                ],
                member: [
                    v => !!v || "人数は必須です。",
                    v =>
                        (v && v >= 1 && v <= 999999) ||
                        "人数は1~999999の範囲で入力してください。"
                ],
                system: [
                    v =>
                        (v && v.length <= 100) ||
                        "システムは100個以下で入力してください。"
                ],
                region: [
                    v =>
                        (v && v.length <= 100) ||
                        "領域は100個以下で入力してください。"
                ],
                role: [
                    v => v.length !== 0 || "役割は必須です。",
                    v =>
                        (v && v.length <= 100) ||
                        "役割は100個以下で入力してください。"
                ],
                dev_method: [
                    v =>
                        (v && v.length <= 100) ||
                        "開発手法は100個以下で入力してください。"
                ],
                process: [
                    v => v.length !== 0 || "工程は必須です。",
                    v =>
                        (v && v.length <= 100) ||
                        "工程は100個以下で入力してください。"
                ],
                lang_and_fw: [
                    v =>
                        (v && v.length <= 100) ||
                        "言語・フレームワークは100個以下で入力してください。"
                ],
                os_and_mw: [
                    v =>
                        (v && v.length <= 100) ||
                        "OS・MWは100個以下で入力してください。"
                ],
                tool: [
                    v =>
                        (v && v.length <= 100) ||
                        "ツールは100個以下で入力してください。"
                ],
                experience_detail: [
                    v => !!v || "経験詳細は必須です。",
                    v =>
                        (v && v.length <= 1000) ||
                        "経験詳細は1000文字以下で入力してください。"
                ]
            },
            isActiveStartedAtPicker: false,
            isActiveEndedAtPicker: false
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
                    title: "",
                    started_at: null,
                    ended_at: null,
                    summary: "",
                    member: null,
                    system: [],
                    region: [],
                    role: [],
                    dev_method: [],
                    process: [],
                    lang_and_fw: [],
                    os_and_mw: [],
                    tool: [],
                    experience_detail: ""
                };
            } else {
                for (let [key, value] of Object.entries(
                    this.projects[index - 1]
                )) {
                    this.editForm[key] = value;
                }
            }
        },
        async ref() {
            this.$store.commit("view/setApiStatus", null);
            const response = await axios.get(
                `/api/skills/${this.$route.params.id}/projects`
            );
            const projects = response.data || [];

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.projects = projects;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);
            // エラーメッセージ
            this.$toast.error("プロジェクト情報を取得できませんでした");
        },
        async edit() {
            this.$emit("input", true);
            const response = await axios.post(
                `/api/skills/${this.$route.params.id}/projects`,
                { project_list: this.projects },
                { headers: { "X-HTTP-Method-Override": "PUT" } }
            );
            const projects = response.data || [];

            if (response.status === CREATED) {
                this.$emit("input", false);
                this.projects = projects;

                // 更新成功メッセージ
                this.$toast.success("プロジェクト情報を更新しました");

                return false;
            }

            this.$emit("input", false);
            this.$store.commit("error/setCode", response.status);
            if (response.status === UNPROCESSABLE_ENTITY) {
                // バリデーションエラー
                // 何もしない
            }

            // エラーメッセージ
            this.$toast.error("プロジェクト情報を更新できませんでした");
        },
        saveStartedAt(date) {
            this.$refs.startedAtMenu.save(date);
        },
        saveEndedAt(date) {
            this.$refs.endedAtMenu.save(date);
        },
        addProject() {
            if (!this.validate()) {
                // バリデーションエラー

                // エラーメッセージ
                this.$toast.error("入力エラーがあります");

                return false;
            }

            const project = {};

            // コピー
            for (let [key, value] of Object.entries(this.editForm)) {
                project[key] = value;
            }

            // 新規であれば追加、既存であれば更新
            if (this.isNewProject) {
                this.projects.push(project);
            } else {
                this.projects[this.activeProject - 1] = project;
            }
            this.resetValidation();
            this.setEditForm();

            // 成功メッセージ
            this.$toast.success(
                "プロジェクトを" +
                    (this.isNewProject ? "追加" : "更新") +
                    "しました。"
            );
            this.$vuetify.goTo(0, this.scrollOption);
            this.activeProject = 0;
        },
        removeProject(index) {
            this.activeProject = 0;
            this.projects.splice(index, 1);

            // 成功メッセージ
            this.$toast.success("プロジェクトを削除しました。");
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
        setActiveProject() {
            if (this.isNewProject) {
                this.setEditForm();
            } else {
                this.setEditForm(this.activeProject);
            }
            this.resetValidation();
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        sortProjects() {
            return this.projects.sort((a, b) =>
                this.$moment(a.started_at, "YYYY-MM").isBefore(
                    this.$moment(b.started_at, "YYYY-MM")
                )
                    ? 1
                    : -1
            );
        },
        calculatePeriodAll() {
            const result = [];

            this.projects.map((project, key) => {
                result.push(
                    this.calculatePeriod(project.started_at, project.ended_at)
                );
            });

            return result;
        },
        isNewProject() {
            return !this.activeProject || this.activeProject === 0;
        },
        projectSamples() {
            return this.$store.getters["constant/project"];
        }
    },
    watch: {
        $route(to, from) {
            this.ref();
        },
        isActiveStartedAtPicker(val) {
            val &&
                setTimeout(
                    () => (this.$refs.startedAtPicker.activePicker = "YEAR")
                );
        },
        isActiveEndedAtPicker(val) {
            val &&
                setTimeout(
                    () => (this.$refs.endedAtPicker.activePicker = "YEAR")
                );
        }
    }
};
</script>

<style scoped></style>
