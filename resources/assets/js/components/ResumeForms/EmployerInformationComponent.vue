<template>
    <div class="form-employer-information">
        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('employer-name')">Employer name</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Enter employer name"
                       v-bind:id="getHashedElementId('employer-name')"
                       v-model="formData.employerName">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('industry')">Industry</label>
            </div>

            <div class="col-sm-8">
                <select class="custom-select d-block w-100"
                        v-bind:id="getHashedElementId('industry')"
                        v-model="formData.industry">
                    <option value="" selected disabled>Choose...</option>
                    <option v-for="(industry, index) in industries"
                            v-bind:index="index"
                            v-bind:key="index"
                            v-bind:value="industry"
                            v-text="industry"></option>
                </select>
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
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import HandleFormDataMixin from "./../../mixins/HandleFormDataMixin.js";

    export default {
        computed: {
            ...mapGetters(["countries", "industries"])
        },

        data() {
            return {
                formData: {
                    city: "",
                    country: "",
                    county: "",
                    employerName: "",
                    industry: ""
                }
            };
        },

        mixins: [
            ComponentHashMixin,
            HandleFormDataMixin
        ]
    };
</script>
