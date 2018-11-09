export default {
    methods: {
        updateSectionName(name) {
            this.$store.dispatch("updateSectionName", {
                index: this.index,
                name: name
            });
        }
    }
};
