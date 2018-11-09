/**
 * @export
 * @class  Section
 * @author Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
export default class Section {
    /**
     * Creates a new instance of Section.
     *
     * @param props
     */
    constructor(props) {
        this.setData(props.data);
        this.setHash(props.hash);
        this.setIsDefault(props.is_default);
        this.setHasNameEditable(props.has_name_editable);
        this.setName(props.name);
        this.setType(props.type);
    }

    /**
     * Returns the hash set for the component.
     *
     * @returns {string}
     */
    getComponentHash() {
        return this.getType() + "-" + this.getHash();
    }

    /**
     * Returns the data of the section.
     *
     * @returns {Object}
     */
    getData() {
        return this.data;
    }

    /**
     * Returns the hash set for the section.
     *
     * @returns {string}
     */
    getHash() {
        return this.hash;
    }

    /**
     * Determines whether the section is default and should be loaded for
     * the fresh resume.
     *
     * @returns {boolean|*}
     */
    getIsDefault() {
        return this.is_default;
    }

    /**
     * Determines whether the name of the section is editable.
     *
     * @returns {boolean|*}
     */
    getHasNameEditable() {
        return this.has_name_editable;
    }

    /**
     * Returns the name of the section.
     *
     * @returns {string}
     */
    getName() {
        return this.name;
    }

    /**
     * Returns the type of the section.
     *
     * @returns {string}
     */
    getType() {
        return this.type;
    }

    /**
     * Sets the data of the section.
     *
     * @param data
     */
    setData(data) {
        this.data = data;
    }

    /**
     * Determines whether the section is default and should be loaded for
     * the fresh resume.
     *
     * @param is_default
     */
    setIsDefault(is_default) {
        this.is_default = is_default ? true : false;
    }

    /**
     * Determines whether the name of the section is editable.
     *
     * @param is_editable
     */
    setHasNameEditable(is_editable) {
        this.has_name_editable = is_editable === false ? false : true;
    }

    /**
     * Sets the hash for the section.
     *
     * @param hash
     */
    setHash(hash) {
        this.hash = hash;
    }

    /**
     * Sets the name for the section.
     *
     * @param name
     */
    setName(name) {
        this.name = name;
    }

    /**
     * Sets the type of the section.
     *
     * @param type
     */
    setType(type) {
        this.type = type;
    }
}
