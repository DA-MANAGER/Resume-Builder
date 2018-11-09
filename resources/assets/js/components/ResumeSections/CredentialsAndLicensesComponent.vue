<template>
    <div>
        <div class="row align-items-center mb-3">
            <div class="col-sm text-muted">
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
                <form-bullet-list-component
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

    export default {
        components: {
            FormBulletListComponent,
            ResumeTitleComponent
        },

        created() {
            this.bulletListIndex = this.getFormIndex();
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
        }
    };
</script>
