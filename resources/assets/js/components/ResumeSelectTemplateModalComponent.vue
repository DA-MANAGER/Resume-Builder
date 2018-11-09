<template>
    <div class="modal fade" id="select-template-modal" tabindex="-1" role="dialog" aria-labelledby="select-template-modal-title"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="select-template-modal-title">
                        <strong>Select a template</strong>
                    </h6>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <p class="text-muted">Click on the template to select.</p>

                    <div class="owl-carousel">
                        <div class="item"
                            v-for="(template, index) in templates"
                            v-bind:index="index"
                            v-bind:key="template.name"
                            v-on:click="setActiveTemplate(template.name)"
                            v-bind:style="{'background-image': 'url(' + template.preview + ')'}">
                            <h5 class="bg-warning text-capitalize font-weight-bold px-4 py-2 rounded shadow"
                                v-text="template.name"></h5>
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
            ...mapGetters(["templates"]),
        },

        methods: {
            hideModal() {
                $("#select-template-modal").modal("hide");
            },

            setActiveTemplate(template) {
                this.$store.dispatch("updateResumeTemplate", template);
                this.hideModal();
            }
        }
    };
</script>

<style lang="sass" scoped>
    .item
        align-items: center
        background-color: #F5F5F5
        background-repeat: no-repeat
        background-size: cover
        cursor: pointer
        display: flex
        height: 420px
        justify-content: center
</style>
