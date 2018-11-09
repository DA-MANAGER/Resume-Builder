/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

// Import the packages required to keep the application running.
import VuexStore from "./store";

// Set the prototype properties to make them accessible by default to all
// the components without having to import them explicitly.
Vue.prototype.$CSRF_TOKEN = window.CSRF_TOKEN;
Vue.prototype.$http = window.axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    "resume-form-component",
    require("./components/ResumeFormComponent.vue")
);

Vue.component(
    "resume-add-section-modal-component",
    require("./components/ResumeAddSectionModalComponent.vue")
);

Vue.component(
    "resume-assign-author-component",
    require("./components/ResumeAssignAuthorComponent.vue")
);

Vue.component(
    "resume-display-template-name-component",
    require("./components/ResumeDisplayTemplateNameComponent.vue")
);

Vue.component(
    "resume-download-form-component",
    require("./components/ResumeDownloadFormComponent.vue")
);

Vue.component(
    "resume-preview-modal-component",
    require("./components/ResumePreviewModalComponent.vue")
);

Vue.component(
    "resume-select-template-modal-component",
    require("./components/ResumeSelectTemplateModalComponent.vue")
);

const app = new Vue({
    el: "#app",
    store: VuexStore
});
