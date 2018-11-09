<template>
    <div class="form-contact-information">
        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('first-name')">First name</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="John"
                       v-bind:id="getHashedElementId('first-name')"
                       v-model="formData.firstName">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('last-name')">Last name</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Smith"
                       v-bind:id="getHashedElementId('last-name')"
                       v-model="formData.lastName">
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
                       v-bind:for="getHashedElementId('post-code')">Post code</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="244713"
                       v-bind:id="getHashedElementId('post-code')"
                       v-model="formData.postCode">
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
                       v-bind:for="getHashedElementId('address-1')">Address Line 1</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Address Line 1"
                       v-bind:id="getHashedElementId('address-1')"
                       v-model="formData.address1">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('address-2')">Address Line 2</label>
             </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Address Line 2"
                       v-bind:id="getHashedElementId('address-2')"
                       v-model="formData.address2">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('phone')">Phone number</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="+353 20 911 2769"
                       v-bind:id="getHashedElementId('phone')"
                       v-model="formData.phone">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('email')">Email address</label>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="email@example.com"
                       v-bind:id="getHashedElementId('email')"
                       v-model="formData.email">
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
            ...mapGetters(["countries"])
        },

        data () {
            return {
                formData: {
                    address1: '',
                    address2: '',
                    city: '',
                    country: '',
                    county: '',
                    email: '',
                    firstName: '',
                    lastName: '',
                    phone: '',
                    postCode: ''
                }
            };
        },

        mixins: [
            ComponentHashMixin,
            HandleFormDataMixin
        ]
    };
</script>
