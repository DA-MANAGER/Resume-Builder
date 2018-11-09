export default {
    created() {
        this.setSecretHash();
    },

    data() {
        return {
            secretHash: undefined
        };
    },

    methods: {
        /**
         * Generates a secret hash for the component to make it unique even
         * when repeated multiple times in other components.
         *
         * @returns {Number}
         */
        generateSecretHash() {
            let number = Math.ceil(Math.random() * 1000000000);
            return number * number;
        },

        /**
         * Returns the hash version for the supplied element name.
         *
         * @param   {String} element
         * @returns {String}
         */
        getHashedElementId(element) {
            return element + "-" + this.getSecretHash();
        },

        /**
         * Returns the generated secret hash of the component.
         *
         * @returns {String}
         */
        getSecretHash() {
            return this.secretHash;
        },

        /**
         * Sets the secret hash for the component.
         *
         * @param   {Number} hash
         * @returns {void}
         */
        setSecretHash(hash = undefined) {
            this.secretHash =
                hash === undefined ? this.generateSecretHash() : hash;
        }
    }
};
