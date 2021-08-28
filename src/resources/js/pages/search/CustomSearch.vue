<template>
    <v-form>
        <div class="mb-3">
            <v-btn color="white" @click="clearSearchForm()">検索条件をクリアする</v-btn>
            <v-btn
                color="primary"
                @click="search()"
                :loading="isSearchLoading"
                :disabled="isSearchLoading"
            >検索</v-btn>
        </div>

        <!-- プロフィール -->
        <v-card>
            <v-card-subtitle>プロフィール</v-card-subtitle>
            <v-card-text>
                <v-row dense>
                    <!-- 名前 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.profile.name" label="名前" />
                    </v-col>

                    <!-- 年齢 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-layout>
                            <v-text-field
                                v-model="searchForm.profile.age"
                                label="年齢"
                                type="number"
                                min="1"
                                max="100"
                            />
                            <span style="width: 70px;">
                                <v-select
                                    v-model="searchForm.profile.age_operator"
                                    :items="searchOperatorConst['NUMBER']"
                                    hide-details
                                ></v-select>
                            </span>
                        </v-layout>
                    </v-col>

                    <!-- 経験年数 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-layout>
                            <v-text-field
                                v-model="searchForm.profile.experience_period"
                                label="経験年数"
                                type="number"
                                min="0"
                                max="40"
                            />
                            <span style="width: 70px;">
                                <v-select
                                    v-model="searchForm.profile.experience_period_operator"
                                    :items="searchOperatorConst['NUMBER']"
                                    hide-details
                                ></v-select>
                            </span>
                        </v-layout>
                    </v-col>

                    <!-- 性別 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <span class="light--text caption">性別</span>
                        <v-radio-group v-model="searchForm.profile.sex" class="mt-0" row dense>
                            <v-radio label="男性"></v-radio>
                            <v-radio label="女性"></v-radio>
                            <v-radio label="その他"></v-radio>
                        </v-radio-group>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- スキル -->
        <v-card class="mt-2">
            <v-card-subtitle>スキル</v-card-subtitle>
            <v-card-text>
                <v-row dense>
                    <!-- スキル名 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.skill.name" label="スキル名" />
                    </v-col>

                    <!-- スキルタイプ -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.skill.type" label="スキルタイプ" />
                    </v-col>

                    <!-- 習熟度 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <span class="light--text caption">習熟度</span>
                        <v-layout>
                            <v-rating
                                v-model="searchForm.skill.level"
                                :length="5"
                                :half-increments="true"
                                background-color="orange lighten-3"
                                color="orange"
                                class="mt-0"
                            ></v-rating>
                            <span style="width: 100px;">
                                <v-select
                                    v-model="searchForm.skill.level_operator"
                                    :items="searchOperatorConst['NUMBER']"
                                    hide-details
                                    dense
                                    solo
                                ></v-select>
                            </span>
                        </v-layout>
                    </v-col>

                    <!-- 経験期間 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-layout>
                            <v-text-field
                                v-model="searchForm.skill.experience_period"
                                label="経験期間"
                                type="number"
                                min="0"
                                max="40"
                            />
                            <span style="width: 70px;">
                                <v-select
                                    v-model="searchForm.skill.experience_period_operator"
                                    :items="searchOperatorConst['NUMBER']"
                                    hide-details
                                ></v-select>
                            </span>
                        </v-layout>
                    </v-col>

                    <!-- ツール -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.skill.tool" label="ツール" />
                    </v-col>

                    <!-- プラットフォーム -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.skill.platform" label="プラットフォーム" />
                    </v-col>

                    <!-- システム -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.skill.system" label="システム" />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- プロジェクト -->
        <v-card class="mt-2">
            <v-card-subtitle>プロジェクト</v-card-subtitle>
            <v-card-text>
                <v-row dense>
                    <!-- プロジェクト名 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.title" label="プロジェクト名" />
                    </v-col>

                    <!-- 開始年月 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-menu
                            ref="startedAtMenu"
                            v-model="isActiveStartedAtPicker"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-layout>
                                    <v-text-field
                                        v-model="searchForm.project.started_at"
                                        label="開始年月"
                                        prepend-inner-icon="event"
                                        readonly
                                        v-on="on"
                                        @keyup="isActiveStartedAtPicker = true"
                                        @keydown="isActiveStartedAtPicker = false"
                                    ></v-text-field>
                                    <span style="width: 70px;">
                                        <v-select
                                            v-model="searchForm.project.started_at_operator"
                                            :items="searchOperatorConst['DATETIME']"
                                            hide-details
                                        ></v-select>
                                    </span>
                                </v-layout>
                            </template>
                            <v-date-picker
                                v-model="searchForm.project.started_at"
                                ref="startedAtPicker"
                                type="month"
                                locale="ja"
                                :max="searchForm.project.ended_at ? searchForm.project.ended_at : new Date().toISOString().substr(0, 10)"
                                min="1950-01-01"
                                @change="saveStartedAt"
                            ></v-date-picker>
                        </v-menu>
                    </v-col>

                    <!-- 終了年月 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-menu
                            ref="endedAtMenu"
                            v-model="isActiveEndedAtPicker"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-layout>
                                    <v-text-field
                                        v-model="searchForm.project.ended_at"
                                        label="終了年月"
                                        prepend-inner-icon="event"
                                        readonly
                                        v-on="on"
                                        @keyup="isActiveEndedAtPicker = true"
                                        @keydown="isActiveEndedAtPicker = false"
                                    ></v-text-field>
                                    <span style="width: 70px;">
                                        <v-select
                                            v-model="searchForm.project.ended_at_operator"
                                            :items="searchOperatorConst['DATETIME']"
                                            hide-details
                                        ></v-select>
                                    </span>
                                </v-layout>
                            </template>
                            <v-date-picker
                                v-model="searchForm.project.ended_at"
                                ref="endedAtPicker"
                                type="month"
                                locale="ja"
                                :max="new Date().toISOString().substr(0, 10)"
                                :min="searchForm.project.started_at ? searchForm.project.started_at :'1950-01-01'"
                                @change="saveEndedAt"
                            ></v-date-picker>
                        </v-menu>
                    </v-col>

                    <!-- 人数 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-layout>
                            <v-text-field v-model="searchForm.project.member" label="人数" />
                            <span style="width: 70px;">
                                <v-select
                                    v-model="searchForm.project.member_operator"
                                    :items="searchOperatorConst['NUMBER']"
                                    hide-details
                                ></v-select>
                            </span>
                        </v-layout>
                    </v-col>

                    <!-- システム -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.system" label="システム" />
                    </v-col>

                    <!-- 領域 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.region" label="領域" />
                    </v-col>

                    <!-- 役割 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.role" label="役割" />
                    </v-col>

                    <!-- 開発手法 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.dev_method" label="開発手法" />
                    </v-col>

                    <!-- 工程 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.process" label="工程" />
                    </v-col>

                    <!-- 言語・FW -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.lang_and_fw" label="言語・FW" />
                    </v-col>

                    <!-- OS・MW -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.os_and_mw" label="OS・MW" />
                    </v-col>

                    <!-- ツール -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.project.tool" label="ツール" />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <!-- 資格 -->
        <v-card class="mt-2">
            <v-card-subtitle>資格</v-card-subtitle>
            <v-card-text>
                <v-row dense>
                    <!-- 資格名 -->
                    <v-col cols="12" xl="3" lg="4" md="6" sm="6" xs="12">
                        <v-text-field v-model="searchForm.qualification.name" label="資格名" />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <div class="mt-3">
            <v-btn color="white" @click="clearSearchForm()">検索条件をクリアする</v-btn>
            <v-btn
                color="primary"
                @click="search()"
                :loading="isSearchLoading"
                :disabled="isSearchLoading"
            >検索</v-btn>
        </div>
    </v-form>
</template>

<script>
import { OK } from "../../util";

export default {
    name: "CustomSearch",
    props: ["value"],
    data() {
        return {
            searchForm: {},
            isActiveStartedAtPicker: false,
            isActiveEndedAtPicker: false,
            isSearchLoading: false
        };
    },
    beforeCreate() {},
    created() {
        // 保持しておいた検索条件をセット
        // 保持していなければ初期値をセット
        if (this.$store.state.search.customSearch === null) {
            this.clearSearchForm();
        } else {
            this.searchForm = this.$store.state.search.customSearch;
        }
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        clearSearchForm() {
            this.$emit("input", null);

            // 検索条件を破棄
            this.$store.commit("search/setCustomSearch", null);

            this.searchForm = {
                profile: {
                    name: "",
                    age: null,
                    age_operator: this.searchOperatorConst["NUMBER"][0],
                    experience_period: null,
                    experience_period_operator: this.searchOperatorConst[
                        "NUMBER"
                    ][0],
                    sex: null
                },
                skill: {
                    name: "",
                    type: "",
                    level: null,
                    level_operator: this.searchOperatorConst["NUMBER"][0],
                    experience_period: null,
                    experience_period_operator: this.searchOperatorConst[
                        "NUMBER"
                    ][0],
                    tool: "",
                    platform: "",
                    system: ""
                },
                project: {
                    title: "",
                    started_at: null,
                    started_at_operator: this.searchOperatorConst[
                        "DATETIME"
                    ][0],
                    ended_at: null,
                    ended_at_operator: this.searchOperatorConst["DATETIME"][0],
                    member: null,
                    member_operator: this.searchOperatorConst["NUMBER"][0],
                    system: "",
                    region: "",
                    role: "",
                    dev_method: "",
                    process: "",
                    lang_and_fw: "",
                    os_and_mw: "",
                    tool: "",
                    experience_detail: ""
                },
                qualification: {
                    name: ""
                }
            };
        },
        async search() {
            if (this.isSearchLoading) {
                return false;
            }

            this.isSearchLoading = true;
            const response = await axios.post(
                "/api/search/custom",
                this.searchForm
            );
            const idList = response.data || [];

            if (response.status === OK) {
                this.isSearchLoading = false;
                this.$emit("input", idList);

                return false;
            }

            this.isSearchLoading = false;
        },
        saveStartedAt(date) {
            this.$refs.startedAtMenu.save(date);
        },
        saveEndedAt(date) {
            this.$refs.endedAtMenu.save(date);
        }
    },
    computed: {
        searchOperatorConst() {
            const allConst = this.$store.getters["constant/all"];
            return allConst["SEARCH_OPERATOR_LABEL"];
        }
    },
    watch: {
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
        },
        searchForm: {
            handler: function(conditions) {
                // 検索条件を保持
                this.$store.commit("search/setCustomSearch", conditions);
            },
            deep: true
        }
    }
};
</script>

<style scoped>
</style>
