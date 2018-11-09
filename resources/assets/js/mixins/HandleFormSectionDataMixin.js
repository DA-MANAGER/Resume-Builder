import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["resume"])
    },

    data() {
        return {
            resume_sections: {}
        };
    },

    watch: {
        resume: {
            deep: true,
            immediate: true,
            handler: function (resume) {
                if (resume !== undefined) {
                    this.resume_sections = resume.getSections();
                }
            }
        }
    }
};