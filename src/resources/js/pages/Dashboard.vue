<template>
    <v-container>
        <v-row dense>
            <v-col cols="12">
                <!-- 全部検索 -->
                <search-all v-model="allApplicable"></search-all>
            </v-col>
            <v-col cols="12">
                <v-expansion-panels v-model="isOpenSearchOption">
                    <v-expansion-panel class="green white--text">
                        <v-expansion-panel-header>
                            詳細検索
                            <template v-slot:actions>
                                <v-icon color="white">$expand</v-icon>
                            </template>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <custom-search v-model="customApplicable"></custom-search>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-col>

            <!-- スケルトンローダー -->
            <v-col
                v-for="key in skeletonLoaderFilter"
                :key="key"
                cols="12"
                xl="4"
                lg="6"
                md="6"
                sm="12"
                xs="12"
            >
                <v-skeleton-loader elevation="3" type="article"></v-skeleton-loader>
            </v-col>

            <v-col
                v-for="card in cardsFilter"
                :key="card.id"
                cols="12"
                xl="4"
                lg="6"
                md="6"
                sm="12"
                xs="12"
            >
                <v-card :to="'/skill/'+ card.id" min-height="100%" link hover>
                    <v-list-item>
                        <v-list-item-avatar class="mb-auto" size="40">
                            <v-img :src="'/api/icons/' + card.id"></v-img>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title
                                class="d-flex justify-space-between flex-wrap align-center"
                            >
                                <span>{{ card.name }}</span>
                                <sex class="ml-2" :sex="card.profile.sex"></sex>
                            </v-list-item-title>
                            <v-list-item-content>
                                <p class="caption" v-text="selfIntroduction(card)"></p>
                            </v-list-item-content>
                        </v-list-item-content>
                    </v-list-item>
                </v-card>
            </v-col>
            <v-col cols="12" v-if="apiStatus !== null && cardsFilter.length === 0">
                <!-- 表示が0件の場合 -->
                <p
                    class="text-center font-weight-bold grey--text title my-10"
                    v-text="'スキル情報がありません'"
                ></p>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import utils from "./../mixins/util.js";
import { OK } from "../util";
import Sex from "../components/common/Sex.vue";
import SearchAll from "./search/SearchAll.vue";
import CustomSearch from "./search/CustomSearch.vue";

export default {
    name: "Dashboard",
    mixins: [utils],
    components: {
        Sex,
        SearchAll,
        CustomSearch
    },
    data() {
        return {
            cards: [],
            allApplicable: null,
            customApplicable: null,
            isOpenSearchOption: [0]
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
            const response = await axios.get("/api/skills", {
                headers: { Accept: "application/json" }
            });
            const users = response.data || null;

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.cards = users;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);

            // エラーメッセージ
            this.$toast.error("ユーザー情報を取得できませんでした");
        },
        selfIntroduction(item) {
            if (!item.profile.self_introduction) {
                return "";
            }

            let sentenceEnd =
                item.profile.self_introduction.length > 100 ? "..." : "";
            return item.profile.self_introduction.substr(0, 100) + sentenceEnd;
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        cardsFilter() {
            let result = this.cards;

            // 全文検索の絞り込み
            if (this.allApplicable !== null) {
                if (this.allApplicable.length > 0) {
                    result = this.cards.filter(card => {
                        return this.allApplicable.some(id => card.id === id);
                    });
                } else {
                    return [];
                }
            }

            // カスタム検索の絞り込み
            if (this.customApplicable !== null) {
                if (this.customApplicable.length > 0) {
                    result = this.cards.filter(card => {
                        return this.customApplicable.some(id => card.id === id);
                    });
                } else {
                    return [];
                }
            }

            return result;
        },
        skeletonLoaderFilter() {
            const cardAmount = 15;
            return this.apiStatus === null && this.cardsFilter.length === 0
                ? cardAmount
                : 0;
        }
    }
};
</script>

<style scoped>
</style>
