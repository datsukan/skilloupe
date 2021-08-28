<template>
    <div>
        <p class="text-center text--secondary" v-if="qualifications.length === 0" v-text="'情報なし'"></p>
        <v-chip-group column>
            <v-chip
                v-for="(qualification, index) in qualifications"
                :key="index"
                class="mt-0"
            >{{ qualification }}</v-chip>
        </v-chip-group>
    </div>
</template>

<script>
import utils from "../../mixins/util.js";
import { OK } from "../../util";

export default {
    name: "QualificationDetail",
    mixins: [utils],
    data() {
        return {
            qualifications: []
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
                `/api/skills/${this.$route.params.id}/qualifications`
            );
            const qualifications = response.data || [];

            if (response.status === OK) {
                this.$store.commit("view/setApiStatus", true);
                this.qualifications = qualifications;
                return false;
            }

            this.$store.commit("view/setApiStatus", false);
            this.$store.commit("error/setCode", response.status);
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        }
    },
    watch: {
        $route(to, from) {
            this.ref();
        }
    }
};
</script>

<style scoped>
</style>
