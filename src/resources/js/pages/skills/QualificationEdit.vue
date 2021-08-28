<template>
    <v-container>
        <v-form v-if="apiStatus !== null" ref="form" v-model="valid">
            <v-row dense>
                <!-- 資格 -->
                <v-col cols="12">
                    <v-combobox
                        label="資格（任意）"
                        v-model="qualifications"
                        :rules="rules"
                        :items="qualificationSamples"
                        multiple
                        chips
                        hide-selected
                        tabindex="1"
                    ></v-combobox>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<script>
import utils from "../../mixins/util.js";
import { OK, CREATED, UNPROCESSABLE_ENTITY } from "../../util";

export default {
    name: "QualificationEdit",
    props: ["value"],
    mixins: [utils],
    data() {
        return {
            scrollOption: {
                duration: 500
            },
            onScroll: null,
            activeSkill: 0,
            qualifications: [],
            valid: true,
            rules: [
                v =>
                    (v && v.length <= 100) ||
                    "資格は100個以下で入力してください。"
            ]
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
            // エラーメッセージ
            this.$toast.error("資格情報を取得できませんでした");
        },
        async edit() {
            this.$emit("input", true);
            const response = await axios.post(
                `/api/skills/${this.$route.params.id}/qualifications`,
                { qualification_list: this.qualifications },
                { headers: { "X-HTTP-Method-Override": "PUT" } }
            );
            const qualifications = response.data || [];
            if (response.status === CREATED) {
                this.$emit("input", false);
                this.qualifications = qualifications;

                // 更新成功メッセージ
                this.$toast.success("資格情報を更新しました");

                return false;
            }
            this.$emit("input", false);
            this.$store.commit("error/setCode", response.status);
            if (response.status === UNPROCESSABLE_ENTITY) {
                // バリデーションエラー
                // 何もしない
            }

            // エラーメッセージ
            this.$toast.error("資格情報を更新できませんでした");
        },
        validate() {
            return this.$refs.form.validate();
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        qualificationSamples() {
            return this.$store.getters["constant/all"]["QUALIFICATION"];
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
