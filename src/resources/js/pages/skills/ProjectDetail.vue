<template>
    <div>
        <v-row dense v-if="!(apiStatus === null && projects.length === 0)">
            <v-col cols="12" v-if="projects.length === 0">
                <p class="text-center text--secondary" v-text="'情報なし'"></p>
            </v-col>
            <v-col
                cols="12"
                lg="3"
                md="12"
                sm="12"
                xs="12"
                class="pr-lg-4 pr-md-auto"
                v-if="projects.length > 0"
            >
                <v-card max-height="520px" class="overflow-y-auto">
                    <v-list dense>
                        <v-list-item-group
                            v-model="activeProject"
                            active-class="light-blue lighten-4"
                            v-scroll:#scroll-target="onScroll"
                            mandatory
                        >
                            <v-list-item v-for="(project, i) in sortProjects" :key="i">
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
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
            <v-col cols="12" lg="9" md="12" sm="12" xs="12" v-if="projects.length > 0">
                <h3 class="title font-weight-bold mb-4">{{ sortProjects[activeProject].title }}</h3>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-1">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'期間'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        {{
                        sortProjects[activeProject].started_at +
                        " ~ " +
                        sortProjects[activeProject].ended_at +
                        ` (${calculatePeriodAll[activeProject]})`
                        }}
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-1">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'概要'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <p v-text="sortProjects[activeProject].summary"></p>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-1">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'人数'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        {{
                        sortProjects[activeProject].member + "人"
                        }}
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'システム'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(system, index) in sortProjects[activeProject].system"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ system }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'領域'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(region, index) in sortProjects[activeProject].region"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ region }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'役割'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(role, index) in sortProjects[activeProject].role"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ role }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'開発手法'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(dev_method, index) in sortProjects[activeProject]
                                    .dev_method"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ dev_method }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'工程'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(process, index) in sortProjects[activeProject].process"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ process }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'言語・FW'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(lang_and_fw, index) in sortProjects[activeProject]
                                    .lang_and_fw"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ lang_and_fw }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'OS・MW'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(os_and_mw, index) in sortProjects[activeProject]
                                    .os_and_mw"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ os_and_mw }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'ツール'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(tool, index) in sortProjects[activeProject].tool"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ tool }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-1">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'経験詳細'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <p v-text="sortProjects[activeProject].experience_detail"></p>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <!-- スケルトンローダー -->
        <v-row dense v-else>
            <v-col cols="12" lg="3" md="12" sm="12" xs="12" class="pr-lg-4 pr-md-auto">
                <v-card height="330px">
                    <v-skeleton-loader type="list-item-two-line@3"></v-skeleton-loader>
                </v-card>
            </v-col>
            <v-col cols="12" lg="9" md="12" sm="12" xs="12">
                <v-skeleton-loader type="heading"></v-skeleton-loader>
                <v-skeleton-loader type="list-item@5"></v-skeleton-loader>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import utils from "../../mixins/util.js";
import { OK } from "../../util";

export default {
    name: "ProjectDetail",
    mixins: [utils],
    data() {
        return {
            onScroll: null,
            activeProject: 0,
            projects: []
        };
    },
    beforeCreate() {},
    created() {
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
                `/api/skills/${this.$route.params.id}/projects`
            );
            const projects = response.data || null;

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.projects = projects;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);
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

            this.sortProjects.map((project, key) => {
                result.push(
                    this.calculatePeriod(project.started_at, project.ended_at)
                );
            });

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
