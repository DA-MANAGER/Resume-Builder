export default {
    methods: {
        updateResumeName(name) {
            this.$store.dispatch("updateResumeName", name);
        }
    }
};
