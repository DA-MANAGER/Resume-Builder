<template>
    <div>
        <span class="text-muted">Template:</span>&nbsp;
        <span class="badge badge-pill badge-warning" v-text="resume.getTemplate()"></span>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        computed: {
            ...mapGetters([
                "resume"
            ])
        }
    };
</script>
