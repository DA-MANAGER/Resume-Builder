<template>
    <div class="modal fade" id="resume-preview-modal" tabindex="-1" role="dialog" aria-labelledby="resume-preview-modal-title"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-6">
                        <h6 class="modal-title mt-2" id="resume-preview-modal-title">
                            <strong>Select a template for the preview</strong>
                        </h6>
                    </div>

                    <div class="col-6">
                        <div class="buttons text-right">
                            <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">Back</button>

                            <button class="btn btn-primary"
                                    v-on:click="downloadResume">
                                <i class="fa-download"></i>&nbsp;Download
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-sm-2"
                            v-for="(template, index) in templates"
                            v-bind:index="index"
                            v-bind:key="template.name">
                            <div class="text-center"
                                v-bind:class="{'active': template.name === resume.getTemplate()}"
                                v-on:click="fetchPreview(template.name)">
                                <img class="img-thumbnail img-fluid rounded cursor-pointer mx-auto d-block" v-bind:src="template.preview">

                                <p class="font-weight-bold text-capitalize text-muted mt-2 mb-0" v-text="template.name"></p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="img-thumbnail resume-preview">
                                <img class="image-preview"
                                    v-bind:src="preview_src"
                                    v-if="preview_src.length > 0">
                                <h5 class="font-weight-bold text-muted text-center my-5"
                                    v-else>Click on the above above resume designs to generate a preview.</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        computed: {
            ...mapGetters([
                "author",
                "resume",
                "templates"
            ]),
        },

        data() {
            return {
                preview_src: ""
            };
        },

        methods: {
            fetchPreview(template) {
                this.$store.dispatch('updateResumeTemplate', template);

                const PREVIEW_URL = APP_API + '/resumes/preview';

                let params = {
                        data: JSON.stringify(this.resume.getSections()),
                        template: template,
                        title: this.resume.getName()
                    };

                if (typeof this.author === "object") {
                    params.author_id = this.author.id;
                }

                return axios
                    .get(PREVIEW_URL, {
                        params
                    })
                    .then(response => this.preview_src = response.data)
                    .catch(error => {
                        console.log(error.response);
                    });
            },

            downloadResume() {
                let form = $('form[name="download-resume"]');

                if (form.length < 1) {
                    form = $('form[name="resume-new"]');
                }

                form.submit();
            }
        },

        watch: {
            resume: {
                deep: true,
                immediate: true,
                handler: _.debounce( function (resume) {
                    if (resume !== undefined) {
                        this.fetchPreview(
                            resume.getTemplate()
                        );
                    }
                }, 200 )
            }
        }
    };
</script>

<style lang="sass" scoped>
    .img-thumbnail:not(.resume-preview)

        &:hover
            border: 1px solid var(--primary)

    .active
        .img-thumbnail
            border: 1px solid var(--primary)

    .resume-preview
        height: 100%
        min-height: 1035px

        img
            background-color: #FFF
            padding: 20px
            width: 100%
</style>
