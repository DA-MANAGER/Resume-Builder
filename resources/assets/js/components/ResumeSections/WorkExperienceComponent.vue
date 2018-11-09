<template>
    <div>
        <div class="row align-items-center mb-3">
            <div class="col-sm-12 text-muted">
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
                <form-employer-information-component
                        v-bind:form-index="employerInformationIndex"
                        v-bind:initial-data="getInitialFormData(employerInformationIndex)"
                        v-on:form-data-updated="updateSectionFormData"></form-employer-information-component>
                <form-position-information-component
                        v-bind:form-index="positionInformationIndex"
                        v-bind:initial-data="getInitialFormData(positionInformationIndex)"
                        v-on:form-data-updated="updateSectionFormData"></form-position-information-component>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label class="col-form-label font-weight-bold"
                               v-bind:for="getHashedElementId('job-keyword')">Enter job keyword to add pre-written
                            responsibilities</label>
                    </div>

                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder='For example, "Manager", or "Sales"'
                               v-bind:id="getHashedElementId('job-keyword')"
                               v-on:input="fetchOccupations"
                               v-on:keyup.enter="fetchOccupations"
                               v-model="selectedOccupation">
                    </div>
                </div>

                <div class="form-group row" v-if="requestError.length > 0">
                    <div class="col-sm-8 offset-sm-4">
                        <div class="alert alert-warning" role="alert">
                            <p class="mb-0" v-html="requestError"></p>
                        </div>
                    </div>
                </div>

                <div class="form-group row" v-if="responsibilities.length > 0">
                    <div class="col-sm-8 offset-sm-4">
                        <p>Showing responsibilities for occupation: <span class="font-weight-bold" v-text="selectedOccupation"></span></p>

                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action cursor-pointer"
                                v-for="(item, index) in responsibilities"
                                v-bind:index="index"
                                v-bind:key="index"
                                v-on:click="addResponsibility(item, index)">
                                <span v-text="item.name"></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="form-group row" v-if="occupations.length > 0">
                    <div class="col-sm-8 offset-sm-4">
                        <p>Showing <span class="badge badge-primary" v-text="occupations.length"></span> occupations for term: <span class="font-weight-bold" v-text="selectedOccupation"></span></p>

                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action cursor-pointer"
                                v-for="(item, index) in occupations"
                                v-bind:index="index"
                                v-bind:key="index"
                                v-on:click="fetchResponsibilities(item)">
                                <span v-text="item.name"></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <form-bullet-list-component
                        ref="formBulletListComponent"
                        v-bind:form-index="bulletListIndex"
                        v-bind:initial-data="getInitialFormData(bulletListIndex)"
                        v-on:form-data-updated="updateSectionFormData"></form-bullet-list-component>
            </div>
        </div>
    </div>
</template>

<script>
    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import ResetSectionHashMixin from "./../../mixins/ResetSectionHashMixin.js";
    import HandleDeletableSectionMixin from "./../../mixins/HandleDeletableSectionMixin.js";
    import HandleSectionNameMixin from "./../../mixins/HandleSectionNameMixin.js";
    import HandleSectionFormMixin from "./../../mixins/HandleSectionFormMixin.js";
    import ResumeTitleComponent from "./../ResumeTitleComponent.vue";
    import FormBulletListComponent from "./../ResumeForms/BulletListComponent.vue";
    import FormEmployerInformationComponent from "./../ResumeForms/EmployerInformationComponent.vue";
    import FormPositionInformationComponent from "./../ResumeForms/PositionInformationComponent.vue";

    export default {
        components: {
            FormBulletListComponent,
            FormEmployerInformationComponent,
            FormPositionInformationComponent,
            ResumeTitleComponent
        },

        created() {
            this.employerInformationIndex = this.getFormIndex();
            this.positionInformationIndex = this.getFormIndex();
            this.bulletListIndex          = this.getFormIndex();
        },

        data() {
            return {
                occupations: [],
                responsibilities: [],
                selectedOccupation: "",
                requestError: ""
            };
        },

        methods: {
            /**
             * Adds a new responsibility in the list of reponsibilities.
             * 
             * @param   {Object} item
             * @param   {Number} index
             * 
             * @returns {void}
             */
            addResponsibility(item, index) {
                this.responsibilities.splice(index, 1);
                this.$refs.formBulletListComponent.addItem(item.name);
            },

            /**
             * Fetches the occupations based on the typed term.
             * 
             * @returns {void}
             */
            fetchOccupations: _.debounce(function() {
                if (this.selectedOccupation.length < 1) {
                    return;
                }

                // Clear all the error and responsibilities to hide them
                // from screen for each new request.
                this.requestError = "";
                this.responsibilities = [];

                const OCCUPATIONS_URL = APP_API + '/occupations';

                axios
                    .get(OCCUPATIONS_URL, {
                        params: {
                            term: this.selectedOccupation
                        }
                    })
                    .then(response => {
                        const data = response.data.data;

                        this.occupations = [];

                        if (data.length > 0) {
                            this.occupations = data;
                        } else {
                            this.requestError = 'Sorry, no occupation was found for term: <span class="font-weight-bold">' + this.selectedOccupation + "</span>";
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 500),

            /**
             * Fetches the responsibilities for the supplied occupation.
             * 
             * @param   {Object} occupation
             * 
             * @returns {void}
             */
            fetchResponsibilities(occupation) {
                this.selectedOccupation = occupation.name;

                // Clear all the error and occupations to hide them from
                // screen for each new request.
                this.occupations = [];
                this.requestError = "";

                const RESPONSIBILITIES_URL = APP_API + '/occupations/' + occupation.id + '/responsibilities';

                axios
                    .get(RESPONSIBILITIES_URL)
                    .then(response => {
                        const data = response.data.data;

                        this.responsibilities = [];

                        if (data.length > 0) {
                            this.responsibilities = data;
                        } else {
                            this.requestError = 'Sorry, no responsibility was found for occupation: <span class="font-weight-bold">' + this.selectedOccupation + "</span>";
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
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
        },

        watch: {
            selectedOccupation(value) {
                // Reset all occupations, responsibilities and error to
                // hide them from screen whenever the user clears
                // occupation in the input field.
                if (value.length < 1) {
                    this.occupations = [];
                    this.responsibilities = [];
                    this.requestError = "";
                }
            }
        }
    };
</script>