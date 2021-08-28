<template>
    <v-navigation-drawer app clipped height="100%" v-model="navbarDrawer">
        <v-list dense>
            <v-list-item two-line>
                <v-list-item-avatar>
                    <v-img :src="'/api/icons/' + id"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>{{ username }}</v-list-item-title>
                    <v-list-item-subtitle>{{ isManage ? '管理ユーザー' : '一般ユーザー' }}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list-item-group v-for="menu in menus" :key="menu.text" dense>
                <v-list-group v-if="menu.subMenus" active-class="grey lighten-4" no-action>
                    <template v-slot:activator>
                        <v-list-item-action>
                            <v-icon>{{ menu.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-title>{{ menu.text }}</v-list-item-title>
                    </template>

                    <v-list-item
                        v-for="subMenu in menu.subMenus"
                        :key="subMenu.text"
                        :to="subMenu.url"
                        active-class="orange darken-1 white--text"
                        link
                    >
                        <v-list-item-action>
                            <v-icon>{{ subMenu.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-title>{{ subMenu.text }}</v-list-item-title>
                    </v-list-item>
                </v-list-group>

                <v-list-item v-else :to="menu.url" active-class="orange darken-1 white--text" link>
                    <v-list-item-action>
                        <v-icon>{{ menu.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-title>{{ menu.text }}</v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>

        <template v-slot:append>
            <div class="pa-2">
                <v-btn :dark="true" block @click="logout()">
                    <v-icon left>fa-sign-out-alt</v-icon>
                    <span class="font-weight-bold">ログアウト</span>
                </v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
export default {
    name: "Navbar",
    data() {
        return {
            menus: []
        };
    },
    beforeCreate() {},
    created() {},
    beforeMount() {},
    mounted() {
        this.default();
    },
    beforeUpdate() {},
    updated() {},
    methods: {
        async logout() {
            await this.$store.dispatch("auth/logout");

            if (this.apiStatus) {
                this.$router.push("/login");
            }
        },
        default() {
            this.menus = [
                { icon: "fa-th-list fa-fw", text: "ダッシュボード", url: "/" }
            ];

            // 参照専用でない場合は自分のスキルメニューを表示
            if (!this.isReadonly) {
                this.menus.push({
                    icon: "fa-code fa-fw",
                    text: "自分のスキル",
                    url: "/skill/" + this.id
                });
            }

            // 個人設定
            this.menus.push({
                icon: "fa-user-cog fa-fw",
                text: "個人設定",
                url: "/setting",
                subMenus: [
                    {
                        icon: "fa-user-lock fa-fw",
                        text: "パスワード変更",
                        url: "/setting/password/change"
                    }
                ]
            });

            // 管理権限がある場合は管理メニューを表示
            if (this.isManage) {
                this.menus.push({
                    icon: "fa-cog fa-fw",
                    text: "管理",
                    url: "/manage",
                    subMenus: [
                        {
                            icon: "fa-user-plus fa-fw",
                            text: "ユーザー登録",
                            url: "/manage/register"
                        },
                        {
                            icon: "fa-users-cog fa-fw",
                            text: "ユーザー管理",
                            url: "/manage/list"
                        }
                    ]
                });
            }
        }
    },
    computed: {
        apiStatus() {
            return this.$store.state.view.apiStatus;
        },
        id() {
            return this.$store.getters["auth/id"];
        },
        username() {
            return this.$store.getters["auth/username"];
        },
        isReadonly() {
            return this.$store.getters["auth/isReadonly"];
        },
        isManage() {
            return this.$store.getters["auth/isManage"];
        },
        navbarDrawer: {
            get() {
                return this.$store.state.view.navbar;
            },
            set(value) {
                this.$store.commit("view/setNavbar", value);
            }
        }
    }
};
</script>

<style scoped>
</style>
