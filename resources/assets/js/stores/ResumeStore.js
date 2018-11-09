const state = {
    /**
     * The active resume.
     *
     * @type {Object}
     */
    resume: undefined,

    templates: []
};

const mutations = {
    /**
     * Adds a new section to the resume.
     *
     * @param state
     * @param section
     * @constructor
     */
    ADD_SECTION: (state, section) => {
        state.resume.addSection(section);
    },

    /**
     * Deletes the section from the resume.
     *
     * @param state
     * @param index
     * @constructor
     */
    DELETE_SECTION: (state, index) => {
        let sections = state.resume.getSections();

        sections.splice(index, 1);
    },

    /**
     * Sets the current displayed resume properties.
     *
     * @param state
     * @param resume
     * @constructor
     */
    SET_RESUME: (state, resume) => {
        state.resume = resume;
    },

    /**
     * Sets the supplied section as active for the resume.
     *
     * @param state
     * @param section
     */
    SET_ACTIVE_SECTION: (state, section) => {
        state.resume.setActiveSection(section);
    },

    /**
     * Sets the supplied templates as available for the resume.
     *
     * @param state
     * @param templates
     */
    SET_TEMPLATES: (state, templates) => {
        state.templates = templates;
    },

    /**
     * Updates the name of the resume.
     *
     * @param state
     * @param name
     */
    UPDATE_RESUME_NAME: (state, name) => {
        state.resume.setName(name);
    },

    /**
     * Updates the template of the resume.
     *
     * @param state
     * @param template
     */
    UPDATE_RESUME_TEMPLATE: (state, template) => {
        state.resume.setTemplate(template);
    },

    UPDATE_SECTIONS: (state, sections) => {
        state.resume.setSections(sections);
    },

    /**
     * Updates the data of the section of the index.
     *
     * @param state
     * @param props
     * @constructor
     */
    UPDATE_SECTION_DATA: (state, props) => {
        const index = props.index;
        const sectionData  = props.data;

        const sections = state.resume.getSections();
        let section = sections[index];

        section.setData(sectionData);
    },

    /**
     * Updates the name of the section of the index.
     *
     * @param state
     * @param props
     * @constructor
     */
    UPDATE_SECTION_NAME: (state, props) => {
        const index = props.index;
        const name = props.name;

        const sections = state.resume.getSections();
        let section = sections[index];

        section.setName(name);
    }
};

const actions = {
    /**
     * Adds a new section to the resume.
     *
     * @param commit
     * @param section
     */
    addSection: ({ commit }, section) => {
        commit("ADD_SECTION", section);
    },

    /**
     * Deletes the section from the resume.
     *
     * @param commit
     * @param index
     */
    deleteSection: ({ commit }, index) => {
        commit("DELETE_SECTION", index);
    },

    /**
     * Sets the current displayed resume properties.
     *
     * @param commit
     * @param resume
     */
    setResume: ({ commit }, resume) => {
        commit("SET_RESUME", resume);
    },

    /**
     * Sets the supplied section as active for the resume.
     *
     * @param commit
     * @param section
     */
    setActiveSection: ({ commit }, section) => {
        commit("SET_ACTIVE_SECTION", section);
    },

    /**
     * Sets the supplied templates as available for the resume.
     *
     * @param commit
     * @param section
     */
    setTemplates({ commit }, templates) {
        commit("SET_TEMPLATES", templates);
    },

    /**
     * Updates the name of the resume.
     *
     * @param commit
     * @param name
     */
    updateResumeName: ({ commit }, name) => {
        commit("UPDATE_RESUME_NAME", name);
    },

    /**
     * Updates the template of the resume.
     *
     * @param commit
     * @param template
     */
    updateResumeTemplate: ({ commit }, template) => {
        commit("UPDATE_RESUME_TEMPLATE", template);
    },

    updateSections: ({ commit }, data) => {
        commit("UPDATE_SECTIONS", data);
    },

    /**
     * Updates the data of the section of the index.
     *
     * @param commit
     * @param data
     */
    updateSectionData: ({ commit }, data) => {
        commit("UPDATE_SECTION_DATA", data);
    },

    /**
     * Updates the name of the section of the index.
     *
     * @param commit
     * @param data
     */
    updateSectionName: ({ commit }, data) => {
        commit("UPDATE_SECTION_NAME", data);
    }
};

const getters = {
    resume: state => state.resume,
    templates: state => state.templates
};

export default {
    state,
    mutations,
    actions,
    getters
};
