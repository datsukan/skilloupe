export default {
    created() {
        if (this.isLogin) this.setConst();
        this.$vuetify.goTo(0);
    },
    methods: {
        calculatePeriod(startedAt, endedAt) {
            const mStartedAt = this.$moment(startedAt, "YYYY-MM");
            const mEndedAt = this.$moment(endedAt, "YYYY-MM").add(1, "months");

            const years = mEndedAt.diff(mStartedAt, "years");
            const months = mEndedAt.diff(mStartedAt, "months");

            return (
                years + "年" + (months % 12 !== 0 ? (months % 12) + "ヶ月" : "")
            );
        },
        async setConst() {
            // constストアのrefアクションを呼び出す
            const result = await this.$store.dispatch("constant/ref");

            if (result) {
                // なにもしない
            } else {
                // エラーメッセージ
                this.$toast.error("システム設定を取得できませんでした");
            }
        }
    },
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        }
    }
};
