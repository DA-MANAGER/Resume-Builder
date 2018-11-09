import Vue from "vue";
import Vuex from "vuex";
import OptionStore from "./stores/OptionStore";
import ResumeStore from "./stores/ResumeStore";
import UserStore from "./stores/UserStore";

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== "production";

export default new Vuex.Store({
    modules: {
        OptionStore,
        ResumeStore,
        UserStore
    },

    strict: debug
});
