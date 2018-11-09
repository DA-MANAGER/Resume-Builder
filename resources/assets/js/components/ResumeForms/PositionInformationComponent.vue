<template>
    <div class="form-position-information">
        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('position-title')">Position title</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Enter position title"
                       v-bind:id="getHashedElementId('position-title')"
                       v-model="formData.positionTitle">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold">Start date</label>
            </div>

            <div class="col-sm-8">
                <div class="form-row">
                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('start-month')"
                                v-model="formData.startMonth">
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
                                v-bind:id="getHashedElementId('start-year')"
                                v-model="formData.startYear">
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

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold">Still work here?</label>
            </div>

            <div class="col-sm-8">
                <span class="custom-control custom-radio d-inline mr-3">
                    <input name="still-work" class="custom-control-input" checked="" required="" type="radio" value="true"
                           v-bind:id="getHashedElementId('yes')"
                           v-model="formData.stillWork">
                    <label class="custom-control-label"
                           v-bind:for="getHashedElementId('yes')">Yes</label>
                </span>

                <span class="custom-control custom-radio d-inline">
                    <input name="still-work" class="custom-control-input" required="" type="radio" value="false"
                           v-bind:id="getHashedElementId('no')"
                           v-model="formData.stillWork">
                    <label class="custom-control-label"
                           v-bind:for="getHashedElementId('no')">No</label>
                </span>
            </div>
        </div>

        <div class="form-group row" v-if="formData.stillWork === 'false'">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold">End date</label>
            </div>

            <div class="col-sm-8">
                <div class="form-row">
                    <div class="col">
                        <select class="custom-select d-block w-100"
                                v-bind:id="getHashedElementId('end-month')"
                                v-model="formData.endMonth">
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
                                v-bind:id="getHashedElementId('end-year')"
                                v-model="formData.endYear">
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

    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import HandleFormDataMixin from "./../../mixins/HandleFormDataMixin.js";

    export default {
        computed: {
            ...mapGetters(["months", "years"])
        },

        data() {
            return {
                formData: {
                    endMonth: "",
                    endYear: "",
                    positionTitle: "",
                    startMonth: "",
                    startYear: "",
                    stillWork: "true"
                }
            };
        },

        mixins: [
            ComponentHashMixin,
            HandleFormDataMixin
        ]
    };
</script>
