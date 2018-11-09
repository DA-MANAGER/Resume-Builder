<template>
    <div class="modal fade" id="add-section-modal" tabindex="-1" role="dialog" aria-labelledby="add-section-modal-title"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="add-section-modal-title">
                        <strong>Add Extra Information</strong>
                    </h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <div class="form">
                        <div class="form-group">
                            <label for="select-section-type">Select a section type:</label>

                            <select id="select-section-type" class="form-control custom-select my-2"
                                v-model="selectedSectionType">
                                <option v-for="(section, index) in SectionType"
                                        v-bind:key="index"
                                        v-bind:index="index"
                                        v-bind:value="section"
                                        v-text="normalizedSectionName(section)"
                                        v-if="isNotExcludedSection(section)"></option>
                            </select>

                            <p class="text-muted" v-text="sectionIntroductionMessage"></p>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary shadow-sm text-uppercase"
                                    data-dismiss="modal"
                                    v-on:click="addSection()">Add Section
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    import ComponentHashMixin from "./../mixins/ComponentHashMixin.js";
    import Section from "./../classes/Section.js";
    import SectionType from "./../enums/SectionType.js";

    export default {
        computed: {
            ...mapGetters([
                "exclude_section_types",
                "section_types_introduction_messages"
            ]),

            /**
             * Returns a custom introduction message for the selected
             * section type.
             *
             * @returns {String}
             */
            sectionIntroductionMessage() {
                const messages = this.section_types_introduction_messages;

                if (!messages.hasOwnProperty(this.selectedSectionType)) {
                    return "";
                }

                return messages[this.selectedSectionType];
            }
        },

        data() {
            return {
                selectedSectionType: SectionType.AFFILIATIONS,
                SectionType: SectionType
            };
        },

        methods: {
            addSection() {
                const sectionName = this.normalizedSectionName(
                    this.selectedSectionType
                );

                let section = new Section({
                    data: [],
                    hash: this.generateSecretHash(),
                    is_default: false,
                    name: sectionName,
                    type: this.selectedSectionType
                });

                this.$store.dispatch("addSection", section).then(() => {
                    // We need to wait for the section to be rendered
                    // on the screen. Once, the section is added we
                    // can safely switch to it so user can fill in
                    // their information on the newly added section.
                    this.$nextTick(() => {
                        this.$store.dispatch("setActiveSection", section);
                    });
                });
            },

            /**
             * Determines whether the section should be excluded from
             * being added in the resume.
             *
             * @param  {String}  sectionType
             *
             * @returns {Boolean}
             */
            isNotExcludedSection(sectionType) {
                return this.exclude_section_types.indexOf(sectionType) === -1;
            },

            /**
             * Normalizes the section type to present it as a valid
             * section name.
             *
             * @param  {String} sectionType
             *
             * @returns {String}
             */
            normalizedSectionName(sectionType) {
                return _.startCase(_.toLower(sectionType.replace(/-/g, " ")));
            }
        },

        mixins: [ComponentHashMixin]
    };
</script>
