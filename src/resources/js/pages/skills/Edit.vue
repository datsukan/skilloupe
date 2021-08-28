<template>
    <v-container>
        <div class="mb-2">
            <v-btn color="success" @click="nextStep()">{{ step === totalStep ? "最初へ" : "次へ" }}</v-btn>
            <v-btn color="primary" @click="edit()" :disabled="disabledSubmitButton">更新</v-btn>
        </div>

        <v-stepper v-model.number="step" non-linear>
            <v-progress-linear
                :indeterminate="isEditLoading"
                :active="isEditLoading"
                color="primary"
                height="5"
                absolute
                top
            ></v-progress-linear>
            <v-progress-linear
                :indeterminate="isEditLoading"
                :active="isEditLoading"
                color="primary"
                height="5"
                absolute
                bottom
            ></v-progress-linear>
            <v-stepper-header>
                <v-stepper-step :step="1" editable>プロフィール</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :step="2" editable>プロジェクト</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :step="3" editable>スキル</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :step="4" editable>資格</v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content :step="1">
                    <!-- プロフィール -->
                    <profile-edit v-model="isEditProfileLoading" ref="profile"></profile-edit>
                </v-stepper-content>

                <v-stepper-content :step="2">
                    <!-- プロジェクト -->
                    <project-edit v-model="isEditProjectLoading" ref="project"></project-edit>
                </v-stepper-content>

                <v-stepper-content :step="3">
                    <!-- スキル -->
                    <each-skill-edit v-model="isEditEachSkillLoading" ref="eachSkill"></each-skill-edit>
                </v-stepper-content>

                <v-stepper-content :step="4">
                    <!-- スキル -->
                    <qualification-edit v-model="isEditQualificationLoading" ref="qualification"></qualification-edit>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>

        <div class="mt-2">
            <v-btn color="success" @click="nextStep()">{{ step === totalStep ? "最初へ" : "次へ" }}</v-btn>
            <v-btn color="primary" @click="edit()" :disabled="disabledSubmitButton">更新</v-btn>
        </div>

        <v-layout class="fab-container" align-center column>
            <v-tooltip left>
                <template v-slot:activator="{ on }">
                    <v-btn :to="'/skill/' + $route.params.id" v-on="on" dark fab color="blue">
                        <v-icon>mdi-eye</v-icon>
                    </v-btn>
                </template>
                <span>詳細へ戻る</span>
            </v-tooltip>
        </v-layout>
    </v-container>
</template>

<script>
import utils from "../../mixins/util.js";
import { OK } from "../../util";
import ProfileEdit from "./ProfileEdit.vue";
import ProjectEdit from "./ProjectEdit.vue";
import EachSkillEdit from "./EachSkillEdit.vue";
import QualificationEdit from "./QualificationEdit.vue";

export default {
    name: "SkillEdit",
    mixins: [utils],
    components: {
        ProfileEdit,
        ProjectEdit,
        EachSkillEdit,
        QualificationEdit
    },
    data() {
        return {
            step: 1,
            totalStep: 4,
            isEditProfileLoading: false,
            isEditProjectLoading: false,
            isEditEachSkillLoading: false,
            isEditQualificationLoading: false
        };
    },
    beforeCreate() {},
    created() {},
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async edit() {
            if (!this.$refs.profile.validate()) {
                // バリデーションエラー

                // エラーメッセージ
                this.$toast.error("プロフィールに入力エラーがあります");

                return false;
            }
            this.$refs.profile.edit();
            this.$refs.project.edit();
            this.$refs.eachSkill.edit();
            this.$refs.qualification.edit();
        },
        nextStep() {
            if (this.step === this.totalStep) {
                this.step = 1;
            } else {
                this.step++;
            }
            this.$vuetify.goTo(0);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        isEditLoading() {
            return (
                this.isEditProfileLoading ||
                this.isEditProjectLoading ||
                this.isEditEachSkillLoading ||
                this.isEditQualificationLoading
            );
        },
        disabledSubmitButton() {
            return this.apiStatus === null || this.isEditLoading;
        }
    },
    watch: {}
};
</script>

<style scoped></style>
