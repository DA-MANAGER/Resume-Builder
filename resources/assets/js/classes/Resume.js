/**
 * @export
 * @class  Resume
 * @author Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
export default class Resume {
    /**
     * Creates a new instance of Resume.
     *
     * @param props
     */
    constructor(props) {
        this.setName(props.name);
        this.setSections(props.sections);
        this.setTemplate(props.template);

        this.setCreatedAt(props.created_at);
        this.setUpdatedAt(props.updated_at);
    }

    /**
     * Adds a new section in the resume.
     *
     * @param section
     */
    addSection(section) {
        this.sections.push(section);
    }

    /**
     * Returns the active section of the resume.
     *
     * @param getIndex  Determines whether to return the whole section or its index.
     *
     * @returns {*}
     */
    getActiveSection(getIndex = false) {
        const sections = this.getSections();
        const index = sections.findIndex(section => {
            const sectionHash = "#" + section.getComponentHash();
            const navItem = '#resume-sections-tabs li.nav-item a[href="' + sectionHash + '"]';

            if ($(navItem).hasClass('active')) {
                return true;
            }

            return false;
        });

        if (index === -1) {
            return "undefined";
        }

        return getIndex ? index : sections[index];
    }

    /**
     * Returns the name set for the resume.
     *
     * @returns {string}
     */
    getName() {
        return this.name;
    }

    /**
     * Returns the sections of the resume.
     *
     * @returns {Array}
     */
    getSections() {
        return this.sections;
    }

    /**
     * Returns the template of the resume.
     *
     * @returns {string}
     */
    getTemplate() {
        return this.template;
    }

    /**
     * Makes the section active in the resume.
     *
     * @param section
     */
    setActiveSection(section) {
        const sectionHash = "#" + section.getComponentHash();
        const navItem = '#resume-sections-tabs li.nav-item a[href="' + sectionHash + '"]';

        $(navItem).tab("show");
    }

    /**
     * Sets the created_at timestamp of the resume.
     *
     * @param created_at
     */
    setCreatedAt(created_at) {
        this.created_at = created_at;
    }

    /**
     * Sets the name of the resume.
     *
     * @param name
     */
    setName(name) {
        this.name = name;
    }

    /**
     * Sets the sections of the resume.
     *
     * @param sections
     */
    setSections(sections) {
        this.sections = sections;
    }

    /**
     * Sets the template of the resume.
     *
     * @param template
     */
    setTemplate(template) {
        this.template = template;
    }

    /**
     * Sets the updated_at timestamp of the resume.
     *
     * @param updated_at
     */
    setUpdatedAt(updated_at) {
        this.updated_at = updated_at;
    }
}
