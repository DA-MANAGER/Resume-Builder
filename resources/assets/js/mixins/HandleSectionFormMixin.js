export default {
    created() {
        // The property is set this way to make it non-reactive, so Vue
        // does not perform an infinite loop when the getFormIndex method
        // is called.
        this.formIndex = -1;
    },

    methods: {
        /**
         * Returns a new index to bind form data in vuex store.
         * 
         * @returns {Number}
         */
        getFormIndex() {
            return ++this.formIndex;
        },

        /**
         * Returns the initial data from sections provided by the server.
         * 
         * @param   {Number} formIndex 
         * 
         * @returns {Object}
         */
        getInitialFormData(formIndex) {
            const data = this.section.getData();

            return formIndex < data.length ? data[formIndex] : undefined;
        },

        /**
         * Updates the form data in the vuex store.
         * 
         * @param   {Array} props
         * 
         * @returns {void} 
         */
        updateSectionFormData(props) {
            const [formData, formIndex] = props;

            let sectionData = this.section.getData();
            sectionData[formIndex] = formData;

            this.$store.dispatch('updateSectionData', {
                index: this.index,
                data: sectionData
            });
        }
    }
};