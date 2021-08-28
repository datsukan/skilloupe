<template>
    <v-app-bar app clipped-left color="primary" dark>
        <v-app-bar-nav-icon @click.stop="setNavbarDrawer()"></v-app-bar-nav-icon>
        <router-link to="/">
            <v-layout class="d-flex align-center">
                <v-img
                    src="/image/icon_white.png"
                    max-height="36"
                    max-width="36"
                    class="mx-2"
                    contain
                ></v-img>
                <h1 class="headline white--text">Skilloupe</h1>
            </v-layout>
        </router-link>
        <v-progress-linear
            :indeterminate="apiStatus === null"
            :active="apiStatus === null"
            color="cyan"
            height="5"
            absolute
            bottom
        ></v-progress-linear>
    </v-app-bar>
</template>

<script>
export default {
    name: "Appbar",
    data() {
        return {};
    },
    beforeCreate() {},
    created() {},
    beforeMount() {},
    mounted() {},
    beforeUpdate() {},
    updated() {},
    methods: {
        setNavbarDrawer() {
            this.$store.commit("view/setNavbar", !this.navbarDrawer);
        }
    },
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        navbarDrawer() {
            return this.$store.state.view.navbar;
        },
        apiStatus() {
            return this.$store.state.view.apiStatus;
        }
    }
};
</script>

<style scoped>
</style>
