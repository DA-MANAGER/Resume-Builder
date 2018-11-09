<template>
    <div class="form-school-information">
        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('education-type')">Education Types</label>
            </div>

            <div class="col-sm-8">
                <select class="custom-select d-block w-100"
                        v-bind:id="getHashedElementId('education-type')"
                        v-model="formData.educationType">
                        <option v-for="(education_type, index) in EducationType"
                                v-bind:index="index"
                                v-bind:key="index"
                                v-bind:value="education_type"
                                v-text="education_type"></option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('school-name')"
                       v-text="schoolNameLabel"></label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control"
                       v-bind:id="getHashedElementId('school-name')"
                       v-bind:placeholder="schoolNamePlaceholder"
                       v-model="formData.schoolName">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('city')">City/Town</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Los Angeles"
                       v-bind:id="getHashedElementId('city')"
                       v-model="formData.city">
            </div>
        </div>

        <div class="form-group row"
            v-if="formData.educationType !== EducationType.SECONDARY_SCHOOL">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('program-name')">Degree/Program</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control"
                       v-bind:id="getHashedElementId('program-name')"
                       v-bind:placeholder="programNamePlaceholder"
                       v-model="formData.programName">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('country')">Country</label>
            </div>

            <div class="col-sm-8">
                <select class="custom-select d-block w-100"
                        v-bind:id="getHashedElementId('country')"
                        v-model="formData.country">
                    <option value="" selected disabled>Choose...</option>
                    <option v-for="(country, index) in countries"
                            v-bind:index="index"
                            v-bind:key="index"
                            v-bind:value="country"
                            v-text="country"></option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('county')">County / State</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="County / State"
                       v-bind:id="getHashedElementId('county')"
                       v-model="formData.county">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('is-enrolled')">Still enrolled?</label>
            </div>

            <div class="col-sm-8">
                <select class="custom-select d-block w-25"
                        v-bind:id="getHashedElementId('is-enrolled')"
                        v-model="formData.isEnrolled">
                    <option value="N/A">N/A</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>
        </div>

        <div class="form-group row" v-if="formData.isEnrolled === 'true'">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold">Expected completion date</label>
            </div>

            <div class="col-sm-8">
                <div class="form-row">
                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('expected-completion-month')"
                                v-model="formData.expectedCompletionMonth">
                            <option value="" selected disabled>Choose month...</option>
                            <option v-for="(month, index) in months"
                                    v-bind:index="index"
                                    v-bind:key="index"
                                    v-bind:value="month"
                                    v-text="month"></option>
                        </select>
                    </div>

                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('expected-completion-year')"
                                v-model="formData.expectedCompletionYear">
                            <option value="" selected disabled>Choose year...</option>
                            <option v-for="(year, index) in years"
                                    v-bind:index="index"
                                    v-bind:key="index"
                                    v-bind:value="year"
                                    v-text="year"></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row" v-if="formData.isEnrolled === 'false'">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('is-completed')">Completed?</label>
            </div>

            <div class="col-sm-8">
                <select class="custom-select d-block w-25"
                        v-bind:id="getHashedElementId('is-completed')"
                        v-model="formData.isCompleted">
                    <option value="N/A">N/A</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>
        </div>

        <div class="form-group row" v-if="formData.isEnrolled === 'false' && formData.isCompleted === 'true'">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold">Completion date</label>
            </div>

            <div class="col-sm-8">
                <div class="form-row">
                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('completion-month')"
                                v-model="formData.completionMonth">
                            <option value="" selected disabled>Choose month...</option>
                            <option v-for="(month, index) in months"
                                    v-bind:index="index"
                                    v-bind:key="index"
                                    v-bind:value="month"
                                    v-text="month"></option>
                        </select>
                    </div>

                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('completion-year')"
                                v-model="formData.completionYear">
                            <option value="" selected disabled>Choose year...</option>
                            <option v-for="(year, index) in years"
                                    v-bind:index="index"
                                    v-bind:key="index"
                                    v-bind:value="year"
                                    v-text="year"></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row" v-if="formData.isEnrolled === 'false' && formData.isCompleted === 'false'">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold">Last year of enrollment</label>
            </div>

            <div class="col-sm-8">
                <div class="form-row">
                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('last-year-of-enrollment-month')"
                                v-model="formData.lastYearOfEnrollmentMonth">
                            <option value="" selected disabled>Choose month...</option>
                            <option v-for="(month, index) in months"
                                    v-bind:index="index"
                                    v-bind:key="index"
                                    v-bind:value="month"
                                    v-text="month"></option>
                        </select>
                    </div>

                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('last-year-of-enrollment-year')"
                                v-model="formData.lastYearOfEnrollmentYear">
                            <option value="" selected disabled>Choose year...</option>
                            <option v-for="(year, index) in years"
                                    v-bind:index="index"
                                    v-bind:key="index"
                                    v-bind:value="year"
                                    v-text="year"></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    import EducationType from "./../../enums/EducationType.js";
    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import HandleFormDataMixin from "./../../mixins/HandleFormDataMixin.js";

    export default {
        computed: {
            ...mapGetters(["countries", "months", "years"]),

            programNamePlaceholder() {
                switch (this.formData.educationType) {
                    case EducationType.UNIVERSITY:
                        return "E.g.: B.Sc. Biology";
                    case EducationType.BTEC:
                        return "E.g.: Lv-5 in Graphics Design";
                    default:
                        return "Enter course title";
                }
            },

            schoolNameLabel() {
                switch (this.formData.educationType) {
                    case EducationType.SECONDARY_SCHOOL:
                        return "School name";
                    case EducationType.UNIVERSITY:
                        return "University name";
                    default:
                        return "Institution name";
                }
            },

            schoolNamePlaceholder() {
                return "Enter " + this.schoolNameLabel.toLowerCase();
            }
        },

        data() {
            return {
                EducationType: EducationType,
                formData: {
                    city: "",
                    completionMonth: "",
                    completionYear: "",
                    country: "",
                    county: "",
                    educationType: EducationType.SECONDARY_SCHOOL,
                    expectedCompletionMonth: "",
                    expectedCompletionYear: "",
                    isCompleted: "N/A",
                    isEnrolled: "N/A",
                    lastYearOfEnrollmentMonth: "",
                    lastYearOfEnrollmentYear: "",
                    programName: "",
                    schoolName: ""
                }
            };
        },

        mixins: [
            ComponentHashMixin,
            HandleFormDataMixin
        ]
    };
</script>
