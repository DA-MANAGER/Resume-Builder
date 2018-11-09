<template>
    <div class="form-user-registration">
        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('email')">Email address</label>

                <p class="text-muted card-text">This email address will be used to log into the application.</p>
            </div>

            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="email@example.com"
                       v-bind:id="getHashedElementId('email')"
                       v-model="formData.registrationEmail">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('password')">Password</label>
            </div>

            <div class="col-sm-8">
                <input type="password" class="form-control" placeholder="Password"
                       v-bind:id="getHashedElementId('password')"
                       v-model="formData.registrationPassword">
            </div>
        </div>
    </div>
</template>

<script>
    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import HandleFormDataMixin from "./../../mixins/HandleFormDataMixin.js";

    export default {
        data () {
            return {
                formData: {
                    registrationEmail: '',
                    registrationPassword: ''
                }
            };
        },

        mixins: [
            ComponentHashMixin,
            HandleFormDataMixin
        ]
    };
</script>
