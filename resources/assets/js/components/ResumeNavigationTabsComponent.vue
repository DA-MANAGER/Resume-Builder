<template>
    <draggable id="resume-sections-tabs" class="nav nav-tabs mt-4" role="tablist" element="ul"
            v-model='sections'
            :options="{draggable:'.draggable'}">
            <li class="nav-item draggable"
                v-for="(section, index) in sections"
                v-bind:index="index"
                v-bind:key="index">
                <a class="nav-link" data-toggle="tab" role="tab" aria-selected="false"
                    v-bind:id="section.getComponentHash() + '-tab'"
                    v-bind:href="'#' + section.getComponentHash()"
                    v-bind:aria-controls="section.getComponentHash()"
                    v-text="section.getName()"></a>
            </li>

            <li slot="footer" class="nav-item">
                <a class="nav-link" href="#" role="button" data-toggle="modal" data-target="#add-section-modal"><i
                        class="fa-plus mr-1"></i> Extra Information</a>
            </li>
    </draggable>
</template>

<script>
    import { mapGetters } from "vuex";
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },

        computed: {
            ...mapGetters(["resume"]),

            sections: {
                get() {
                    return this.resume.getSections();
                },

                set(newSectionOrder) {
                    this.$store.dispatch('updateSections', newSectionOrder);
                }
            }
        }
    };
</script>
