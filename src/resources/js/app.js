import "./bootstrap";
import Vue from "vue";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
import VuetifyToast from "vuetify-toast-snackbar";
import "@mdi/font/css/materialdesignicons.css";
import "material-design-icons-iconfont/dist/material-design-icons.css";
import "@fortawesome/fontawesome-free/css/all.css";

import moment from "vue-moment";

import "../css/common.css";

import router from "./router";
import store from "./store";
import App from "./App.vue";

Vue.use(moment);
Vue.use(Vuetify);

Vue.use(VuetifyToast, {
    x: "center",
    y: "bottom",
    color: "info",
    icon: "info",
    iconColor: "",
    classes: ["body-2"],
    timeout: 1500,
    dismissable: true,
    multiLine: false,
    vertical: false,
    queueable: true,
    showClose: false,
    closeText: "",
    closeIcon: "close",
    closeColor: "",
    slot: [],
    shorts: {
        custom: {
            color: "purple"
        }
    },
    property: "$toast"
});

const createApp = async () => {
    await store.dispatch("auth/currentUser");

    new Vue({
        el: "#app",
        router,
        store,
        components: { App },
        template: "<App />",
        vuetify: new Vuetify()
    });
};

createApp();
