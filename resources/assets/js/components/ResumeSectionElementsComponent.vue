<template>
    <div class="tab-content">
        <div
                class="tab-pane fade" role="tabpanel"
                v-for="(section, index) in resume.getSections()"
                v-bind:index="index"
                v-bind:key="index"
                v-bind:aria-labelledby="section.getComponentHash() + '-tab'"
                v-bind:id="section.getComponentHash()">

            <section-additional-skills-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-if="section.getType() === SectionType.ADDITIONAL_SKILLS"></section-additional-skills-component>

            <section-additional-skills-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.AFFILIATIONS"></section-additional-skills-component>

            <section-awards-and-honors-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.AWARDS_AND_HONORS"></section-awards-and-honors-component>

            <section-career-objective-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.CAREER_OBJECTIVE"></section-career-objective-component>

            <section-contact-information-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.CONTACT_INFORMATION"></section-contact-information-component>

            <section-credentials-and-licenses-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.CREDENTIALS_AND_LICENSES"></section-credentials-and-licenses-component>

            <section-custom-information-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.CUSTOM_INFORMATION"></section-custom-information-component>

            <section-education-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.EDUCATION"></section-education-component>

            <section-endorsements-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.ENDORSEMENTS"></section-endorsements-component>

            <section-hobbies-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.HOBBIES"></section-hobbies-component>

            <section-publications-and-presentations-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.PUBLICATIONS_AND_PRESENTATIONS"></section-publications-and-presentations-component>

            <section-qualifications-summary-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.QUALIFICATIONS_SUMMARY"></section-qualifications-summary-component>

            <section-work-experience-component
                    v-bind:index="index"
                    v-bind:section="section"
                    v-else-if="section.getType() === SectionType.WORK_EXPERIENCE"></section-work-experience-component>

        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    import SectionType from "./../enums/SectionType.js";

    import SectionAdditionalSkillsComponent from "./ResumeSections/AdditionalSkillsComponent.vue";
    import SectionAffiliationsComponent from "./ResumeSections/AffiliationsComponent.vue";
    import SectionAwardsAndHonorsComponent from "./ResumeSections/AwardsAndHonorsComponent.vue";
    import SectionCareerObjectiveComponent from "./ResumeSections/CareerObjectiveComponent.vue";
    import SectionContactInformationComponent from "./ResumeSections/ContactInformationComponent.vue";
    import SectionCredentialsAndLicensesComponent from "./ResumeSections/CredentialsAndLicensesComponent.vue";
    import SectionCustomInformationComponent from "./ResumeSections/CustomInformationComponent.vue";
    import SectionEducationComponent from "./ResumeSections/EducationComponent.vue";
    import SectionEndorsementsComponent from "./ResumeSections/EndorsementsComponent.vue";
    import SectionHobbiesComponent from "./ResumeSections/HobbiesComponent.vue";
    import SectionPublicationsAndPresentationsComponent from "./ResumeSections/PublicationsAndPresentationsComponent.vue";
    import SectionQualificationsSummaryComponent from "./ResumeSections/QualificationsSummaryComponent.vue";
    import SectionWorkExperienceComponent from "./ResumeSections/WorkExperienceComponent.vue";

    export default {
        components: {
            SectionAdditionalSkillsComponent,
            SectionAffiliationsComponent,
            SectionAwardsAndHonorsComponent,
            SectionCareerObjectiveComponent,
            SectionContactInformationComponent,
            SectionCredentialsAndLicensesComponent,
            SectionCustomInformationComponent,
            SectionEducationComponent,
            SectionEndorsementsComponent,
            SectionHobbiesComponent,
            SectionPublicationsAndPresentationsComponent,
            SectionQualificationsSummaryComponent,
            SectionWorkExperienceComponent
        },

        computed: {
            ...mapGetters(["resume"])
        },

        data() {
            return {
                SectionType: SectionType
            };
        }
    };
</script>
