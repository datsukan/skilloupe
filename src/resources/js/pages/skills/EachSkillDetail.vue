<template>
    <div>
        <v-row dense v-if="!(apiStatus === null && skills.length === 0)">
            <v-col cols="12" v-if="skills.length === 0">
                <p class="text-center text--secondary" v-text="'情報なし'"></p>
            </v-col>
            <v-col
                cols="12"
                lg="3"
                md="12"
                sm="12"
                xs="12"
                class="pr-lg-4 pr-md-auto"
                v-if="skills.length > 0"
            >
                <v-card max-height="330px" class="overflow-y-auto">
                    <v-list dense>
                        <v-list-item-group
                            v-model="activeSkill"
                            active-class="light-blue lighten-4"
                            v-scroll:#scroll-target="onScroll"
                            mandatory
                        >
                            <v-list-item v-for="(skill, i) in sortSkills" :key="i">
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
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
            <v-col cols="12" lg="9" md="12" sm="12" xs="12" v-if="skills.length > 0">
                <h3 class="title font-weight-bold mb-4">{{ sortSkills[activeSkill].name }}</h3>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-1">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'スキルタイプ'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">{{ sortSkills[activeSkill].type }}</v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'習熟度'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-rating
                            v-model.number="sortSkills[activeSkill].level"
                            :length="5"
                            :half-increments="true"
                            :readonly="true"
                            :dense="true"
                            background-color="orange lighten-3"
                            color="orange"
                        ></v-rating>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-1">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'経験期間'"
                        ></p>
                    </v-col>
                    <v-col
                        cols="12"
                        md="10"
                        sm="12"
                        xs="12"
                    >{{ sortSkills[activeSkill].experience_period + '年' }}</v-col>
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
                                v-for="(tool, index) in sortSkills[activeSkill].tool"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ tool }}</v-chip>
                        </v-chip-group>
                    </v-col>
                </v-row>
                <v-row dense>
                    <v-col cols="12" md="2" sm="12" xs="12" class="pr-4 pt-2">
                        <p
                            class="text-md-right text-sm-left subtitle-2 primary--text"
                            v-text="'プラットフォーム'"
                        ></p>
                    </v-col>
                    <v-col cols="12" md="10" sm="12" xs="12">
                        <v-chip-group column>
                            <v-chip
                                v-for="(platform, index) in sortSkills[activeSkill].platform"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ platform }}</v-chip>
                        </v-chip-group>
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
                                v-for="(system, index) in sortSkills[activeSkill].system"
                                :key="index"
                                small
                                class="mt-0"
                            >{{ system }}</v-chip>
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
                        <p v-text="sortSkills[activeSkill].experience_detail"></p>
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
    name: "EachSkillDetail",
    mixins: [utils],
    data() {
        return {
            onScroll: null,
            activeSkill: 0,
            skills: []
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
                `/api/skills/${this.$route.params.id}/each-skills`
            );
            const skills = response.data || null;

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.skills = skills;
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
        sortSkills() {
            return this.skills.sort((a, b) => (b.level > a.level ? 1 : -1));
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
