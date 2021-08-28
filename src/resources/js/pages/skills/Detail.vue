<template>
    <v-container>
        <v-expansion-panels v-model="isPanelOpen" multiple>
            <v-expansion-panel>
                <!-- プロフィール -->
                <v-expansion-panel-header class="title">プロフィール</v-expansion-panel-header>
                <v-expansion-panel-content v-if="skillDetail.user && skillDetail.profile">
                    <v-list-item class="px-0">
                        <v-list-item-content>
                            <div class="d-flex flex-wrap">
                                <v-card
                                    class="mb-3 mr-3 ml-1"
                                    width="100"
                                    height="100"
                                    max-width="100"
                                    max-height="100"
                                >
                                    <v-img :src="'/api/icons/' + skillDetail.user.id"></v-img>
                                </v-card>
                                <div>
                                    <v-list-item-title class="headline">{{ skillDetail.user.name }}</v-list-item-title>
                                    <v-list-item-content>
                                        <p
                                            v-text="`${skillDetail.profile.age}歳  (${skillDetail.profile.experience_period}年目)`"
                                        ></p>
                                        <sex class="mt-2" :sex="skillDetail.profile.sex"></sex>
                                    </v-list-item-content>
                                </div>
                            </div>
                            <v-list-item-content>
                                <p class="mt-2" v-text="skillDetail.profile.self_introduction"></p>
                            </v-list-item-content>
                        </v-list-item-content>
                    </v-list-item>
                </v-expansion-panel-content>

                <!-- スケルトンローダー -->
                <v-expansion-panel-content v-else>
                    <v-list-item>
                        <v-list-item-content>
                            <div class="d-flex flex-wrap">
                                <v-skeleton-loader type="image" width="100" height="100"></v-skeleton-loader>
                                <div>
                                    <v-skeleton-loader type="heading"></v-skeleton-loader>
                                    <v-skeleton-loader type="heading"></v-skeleton-loader>
                                    <v-skeleton-loader type="heading"></v-skeleton-loader>
                                </div>
                            </div>
                            <v-list-item-content>
                                <v-skeleton-loader type="paragraph"></v-skeleton-loader>
                            </v-list-item-content>
                        </v-list-item-content>
                    </v-list-item>
                </v-expansion-panel-content>
            </v-expansion-panel>

            <!-- スキル -->
            <v-expansion-panel>
                <v-expansion-panel-header class="title">スキル</v-expansion-panel-header>
                <v-expansion-panel-content>
                    <each-skill-detail></each-skill-detail>
                </v-expansion-panel-content>
            </v-expansion-panel>

            <!-- プロジェクト -->
            <v-expansion-panel>
                <v-expansion-panel-header class="title">プロジェクト</v-expansion-panel-header>
                <v-expansion-panel-content>
                    <project-detail></project-detail>
                </v-expansion-panel-content>
            </v-expansion-panel>

            <!-- 資格 -->
            <v-expansion-panel>
                <v-expansion-panel-header class="title">資格</v-expansion-panel-header>
                <v-expansion-panel-content>
                    <qualification-detail></qualification-detail>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>

        <v-layout class="fab-container" align-center column>
            <v-tooltip left>
                <template v-slot:activator="{ on }">
                    <v-btn @click="panelOpen()" v-on="on" dark fab small color="green">
                        <v-icon>mdi-arrow-expand-vertical</v-icon>
                    </v-btn>
                </template>
                <span>すべて展開</span>
            </v-tooltip>
            <v-tooltip left>
                <template v-slot:activator="{ on }">
                    <v-btn @click="panelClose()" v-on="on" dark fab small color="red">
                        <v-icon>mdi-arrow-collapse-vertical</v-icon>
                    </v-btn>
                </template>
                <span>すべて縮小</span>
            </v-tooltip>
            <v-tooltip left v-if="isManage || Number($route.params.id) === id">
                <template v-slot:activator="{ on }">
                    <v-btn :to="'/skill/edit/' + $route.params.id" v-on="on" dark fab color="blue">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </template>
                <span>編集</span>
            </v-tooltip>
        </v-layout>
    </v-container>
</template>

<script>
import { OK, UNPROCESSABLE_ENTITY } from "../../util";
import Sex from "../../components/common/Sex.vue";
import ProjectDetail from "./ProjectDetail.vue";
import EachSkillDetail from "./EachSkillDetail.vue";
import QualificationDetail from "./QualificationDetail.vue";

export default {
    name: "SkillDetail",
    components: {
        Sex,
        ProjectDetail,
        EachSkillDetail,
        QualificationDetail
    },
    data() {
        return {
            isPanelOpen: [],
            skillDetail: {}
        };
    },
    beforeCreate() {},
    created() {
        this.panelOpen();
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
                this.skillDetail = skillDetail;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);

            // エラーメッセージ
            this.$toast.error("スキル情報を取得できませんでした");
        },
        panelOpen() {
            this.isPanelOpen = [0, 1, 2, 3];
        },
        panelClose() {
            this.isPanelOpen = [];
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        id() {
            return this.$store.getters["auth/id"];
        },
        isManage() {
            return this.$store.getters["auth/isManage"];
        }
    },
    watch: {
        $route(to, from) {
            this.panelOpen();
            this.ref();
        }
    }
};
</script>

<style scoped>
</style>
