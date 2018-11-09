import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["resume"])
    },

    data() {
        return {
            isBackButtonDisabled: false,
            isNextButtonDisabled: false
        };
    },

    methods: {
        /**
         * Makes the next section active of the resume.
         *
         * @returns {void}
         */
        handleNextSectionButton() {
            const sections = this.resume.getSections();
            let index = this.resume.getActiveSection(true);

            this.$store.dispatch("setActiveSection", sections[++index]);
        },

        /**
         * Makes the previous section active of the resume.
         *
         * @returns {void}
         */
        handlePreviousSectionButton() {
            const sections = this.resume.getSections();
            let index = this.resume.getActiveSection(true);

            this.$store.dispatch("setActiveSection", sections[--index]);
        },

        /**
         * Mutates the state of the section navigation buttons according
         * to the currently active section.
         *
         * @returns {void}
         */
        mutateNavigationButtonsState() {
            const activeSectionIndex = this.resume.getActiveSection(true);
            const totalSections = this.resume.getSections().length - 1;

            this.isBackButtonDisabled = activeSectionIndex <= 0 ? true : false;
            this.isNextButtonDisabled =
                activeSectionIndex >= totalSections ? true : false;
        },

        /**
         * Mutates the completion percentage of the progress bar according
         * to the currently active section.
         *
         * @returns {void}
         */
        mutateProgressBar() {
            const progressbar = $("#resume-completion-progress").find("div");

            const activeSectionIndex = this.resume.getActiveSection(true);
            const totalSections = this.resume.getSections().length;
            const completion = Math.floor(
                (activeSectionIndex + 1) / totalSections * 100
            );

            progressbar.attr("aria-valuenow", completion);
            progressbar.css({ width: completion + "%" });
        }
    },

    mounted() {
        const _this = this;

        !(function($) {
            "use strict";

            const tabs = $("#resume-sections-tabs");

            // We'll wait for the section to render on the screen and then
            // mutate the progress bar and navigation buttons accordingly.
            tabs.on(
                "shown.bs.tab",
                '.nav-item a[data-toggle="tab"]',
                function() {
                    _this.mutateNavigationButtonsState();
                    _this.mutateProgressBar();
                }
            );
        })(window.jQuery);
    }
};
