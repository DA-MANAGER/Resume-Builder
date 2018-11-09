<template>
    <form class="d-inline" name="download-resume" method="post"
        v-bind:action="form_action_url"
        v-bind:id="getHashedElementId('download-resume-form')">
        <input name="_token" type="hidden" :value="$CSRF_TOKEN"/>
        <input name="data" type="hidden" :value="JSON.stringify(resume_sections)"/>

        <button type="submit" class="btn btn-outline-primary">
            <i class="fa-download"></i>
        </button>
    </form>
</template>

<script>
    import ComponentHashMixin from "./../mixins/ComponentHashMixin.js";
    import HandleFormSectionDataMixin from "./../mixins/HandleFormSectionDataMixin.js";

    export default {
        mixins: [
            ComponentHashMixin,
            HandleFormSectionDataMixin
        ],

        props: {
            form_action_url: String
        }
    };
</script>
