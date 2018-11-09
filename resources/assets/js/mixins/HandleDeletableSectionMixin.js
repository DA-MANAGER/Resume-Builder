import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["resume"])
    },

    methods: {
        handleDeleteSection(value) {
            if (true === value) {
                const confirmation = confirm(
                    'Are you sure, you want to delete "' +
                        this.section.getName() +
                        '" section? This action can not be undone!'
                );

                if (true === confirmation) {
                    let index = this.index;

                    this.$store.dispatch("deleteSection", index).then(() => {
                        // We need to wait for the DOM to update. Once,
                        // the DOM is updated, We can proceed and activate
                        // the new section to display on the screen.
                        this.$nextTick(() => {
                            const sections = this.resume.getSections();

                            let section = sections[index];

                            if ("undefined" !== typeof section) {
                                this.$store.dispatch('setActiveSection', section);
                            } else {
                                this.$store.dispatch('setActiveSection', sections[--index]);
                            }
                        });
                    });
                }
            }
        }
    }
};
