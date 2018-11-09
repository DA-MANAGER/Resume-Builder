<template>
    <div>
        <div class="row align-items-center mb-3">
            <div class="col-sm text-muted">
                <resume-title-component
                        v-bind:isDeletable="section.getIsDefault() === false"
                        v-bind:isEditable="section.getHasNameEditable()"
                        v-bind:title="section.getName()"
                        v-on:handle-delete="handleDeleteSection"
                        v-on:title-updated="updateSectionName"></resume-title-component>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form-contact-information-component
                        v-bind:form-index="contactInformationIndex"
                        v-bind:initial-data="getInitialFormData(contactInformationIndex)"
                        v-on:form-data-updated="updateSectionFormData"></form-contact-information-component>

                <div v-if="'object' !== typeof author">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="col-form-label font-weight-bold">Register now?</label>

                            <p class="text-muted card-text">Registered users enjoys the lifetime access to their resume.</p>
                        </div>

                        <div class="col-sm-8">
                            <span class="custom-control custom-radio d-inline mr-3">
                                <input name="registration" class="custom-control-input" required="" type="radio" value="1"
                                    v-bind:id="getHashedElementId('yes')"
                                    v-model="registerUser">
                                <label class="custom-control-label"
                                    v-bind:for="getHashedElementId('yes')">Yes</label>
                            </span>

                            <span class="custom-control custom-radio d-inline">
                                <input name="registration" class="custom-control-input" required="" type="radio" value="0"
                                    v-bind:id="getHashedElementId('no')"
                                    v-model="registerUser">
                                <label class="custom-control-label"
                                    v-bind:for="getHashedElementId('no')">No</label>
                            </span>
                        </div>
                    </div>

                    <form-user-registration-component
                            v-on:form-data-updated="updateRegistrationInformation"
                            v-if="registerUser == '1'"></form-user-registration-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import ResetSectionHashMixin from "./../../mixins/ResetSectionHashMixin.js";
    import HandleDeletableSectionMixin from "./../../mixins/HandleDeletableSectionMixin.js";
    import HandleSectionNameMixin from "./../../mixins/HandleSectionNameMixin.js";
    import HandleSectionFormMixin from "./../../mixins/HandleSectionFormMixin.js";
    import ResumeTitleComponent from "./../ResumeTitleComponent.vue";
    import FormContactInformationComponent from "./../ResumeForms/ContactInformationComponent.vue";
    import FormUserRegistrationComponent from "./../ResumeForms/UserRegistrationComponent.vue";

    export default {
        components: {
            FormContactInformationComponent,
            FormUserRegistrationComponent,
            ResumeTitleComponent
        },

        computed: {
            ...mapGetters(["author"])
        },

        created() {
            this.contactInformationIndex = this.getFormIndex();
        },

        data() {
            return {
                registerUser: "1"
            };
        },

        methods: {
            updateRegistrationInformation(props) {
                const [formData, formIndex] = props;

                this.$store.dispatch('updateRegistrationEmail', formData.registrationEmail);
                this.$store.dispatch('updateRegistrationPassword', formData.registrationPassword);
            }
        },

        mixins: [
            ComponentHashMixin,
            ResetSectionHashMixin,
            HandleDeletableSectionMixin,
            HandleSectionNameMixin,
            HandleSectionFormMixin
        ],

        props: {
            index: Number,
            section: Object
        }
    };
</script>
