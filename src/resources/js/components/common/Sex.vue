<template>
    <span>
        <v-chip color="info" v-if="sex === sexConst['MALE']" outlined>
            <v-icon class="mr-1">mdi-face</v-icon>
            <span>男性</span>
        </v-chip>
        <v-chip color="error" v-else-if="sex === sexConst['FEMALE']" outlined>
            <v-icon class="mr-1">mdi-face-woman</v-icon>
            <span>女性</span>
        </v-chip>
        <v-chip color="success" v-else-if="sex === sexConst['OTHER']" outlined>
            <span>その他</span>
        </v-chip>
    </span>
</template>

<script>
export default {
    name: "Sex",
    props: ["sex"],
    data() {
        return {};
    },
    beforeCreate() {},
    created() {},
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {},
    computed: {
        sexConst() {
            const dbConst = this.$store.getters["constant/db"];
            return dbConst["SEX"];
        }
    }
};
</script>

<style scoped>
</style>
