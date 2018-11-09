export default {
    created() {
        if (this.section.getHash() !== undefined) {
            this.setSecretHash(this.section.getHash());
        }
    }
};
