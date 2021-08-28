<template>
    <v-text-field
        v-model="search"
        prepend-inner-icon="mdi-magnify"
        label="全文検索"
        solo
        clearable
        hide-details
        :loading="isSearchLoading.length !== 0"
    ></v-text-field>
</template>

<script>
import { OK } from "../../util";

export default {
    name: "SearchAll",
    props: ["value"],
    data() {
        return {
            search: "",
            isSearchLoading: []
        };
    },
    beforeCreate() {},
    created() {
        // 保持しておいた検索ワードをセット
        this.search = this.$store.state.search.allSearch;
    },
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        async wordSearch() {
            if (this.search === "" || this.search === null) {
                this.$emit("input", null);
                return false;
            }

            this.isSearchLoading.push(true);
            const word = this.search;
            const response = await axios.get(`/api/search?word=${word}`);
            const idList = response.data || [];

            if (response.status === OK) {
                this.isSearchLoading.pop();

                if (this.search === "" || this.search === null) {
                    this.$emit("input", null);
                } else if (this.search === word) {
                    this.$emit("input", idList);
                }

                return false;
            }

            this.isSearchLoading.pop();
        }
    },
    computed: {},
    watch: {
        search: function(word) {
            this.wordSearch();

            // 検索ワードを保持
            this.$store.commit("search/setAllSearch", word);
        }
    }
};
</script>

<style scoped>
</style>
