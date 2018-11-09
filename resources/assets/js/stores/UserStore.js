const state = {
    author: undefined,

    registration_info: {
        email: "",
        name: "",
        password: ""
    },
};

const mutations = {
    UPDATE_AUTHOR: (state, user) => {
        state.author = user;
    },

    UPDATE_REGISTRATION_EMAIL: (state, email) => {
        state.registration_info.email = email;
    },

    UPDATE_REGISTRATION_NAME: (state, name) => {
        state.registration_info.name = name;
    },

    UPDATE_REGISTRATION_PASSWORD: (state, password) => {
        state.registration_info.password = password;
    },
};

const actions = {
    updateAuthor: ({ commit }, user) => {
        commit("UPDATE_AUTHOR", user);
    },

    updateRegistrationEmail: ({ commit }, email) => {
        commit("UPDATE_REGISTRATION_EMAIL", email);
    },

    updateRegistrationName: ({ commit }, name) => {
        commit("UPDATE_REGISTRATION_NAME", name);
    },

    updateRegistrationPassword: ({ commit }, password) => {
        commit("UPDATE_REGISTRATION_PASSWORD", password);
    },
};

const getters = {
    author: state => state.author,
    registration_info: state => state.registration_info
};

export default {
    state,
    mutations,
    actions,
    getters
};
