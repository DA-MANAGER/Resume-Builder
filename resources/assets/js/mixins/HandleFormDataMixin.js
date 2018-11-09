export default {
    created() {
        if (this.initialData !== undefined) {
            // It it important to clone the data to avoid mutation error
            // before storing it in the form data of the component.
            this.formData = _.clone(this.initialData);
        }
    },

    props: {
        formIndex: {
            default: 0,
            type: Number
        },

        initialData: {
            default: undefined,
            type: Object
        }
    },

    watch: {
        formData: {
            deep: true,
            immediate: true,
            handler: function (formData) {
                this.$emit("form-data-updated", [formData, this.formIndex]);
            }
        }
    }
};