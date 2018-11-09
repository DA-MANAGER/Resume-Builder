<template>
    <div class="dropdown">
        <a class="btn btn-link dropdown-toggle px-0 py-2 w-100 text-left" href="#" role="button" id="dropdown-authors" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="d-inline mr-2">
                <img class="rounded-circle" style="width: 45px; height: 45px;" v-bind:src="authorAvatar">
            </div>

            <span v-text="author.name"></span>
        </a>

        <div class="dropdown-menu w-100" aria-labelledby="dropdown-authors">
            <div class="px-3 py-2">
                <input type="search" class="form-control" placeholder="John Smith" autofocus="autofocus"
                    v-on:input="fetchUsers"
                    v-on:keyup.enter="fetchUsers"
                    v-model="selectedAuthor">
            </div>

            <div>
                <div class="py-2" v-if="authors.length > 0">
                    <div class="dropdown-item cursor-pointer d-flex px-3"
                        v-for="(user, index) in authors"
                        v-bind:index="index"
                        v-bind:key="index"
                        v-on:click="setAuthor(user)">
                        <div class="mr-2 mt-2">
                            <div v-if="user.avatar !== null">
                                <img class="rounded-circle" style="width: 30px; height: 30px;" v-bind:src="user.avatar">
                            </div>

                            <div v-else>
                                <img class="rounded-circle" style="width: 30px; height: 30px;" v-bind:src="avatar">
                            </div>
                        </div>

                        <div>
                            <span class="d-block font-weight-bold text-truncate" style="max-width: 145px;"
                                    v-text="user.name"></span>
                            <span class="text-muted"
                                    v-text="'@' + user.username"></span>
                        </div>
                    </div>
                </div>

                <div class="dropdown-header text-center" v-else>
                    <span>No user found.</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";

    export default {
        computed: {
            ...mapGetters([
                "author"
            ]),

            authorAvatar() {
                return this.author.avatar !== null ? this.author.avatar : this.avatar;
            }
        },

        data() {
            return {
                authors: [],
                selectedAuthor: ""
            };
        },

        methods: {
            /**
             * Fetches the authors based on the typed term.
             * 
             * @returns {void}
             */
            fetchUsers: _.debounce(function() {
                if (this.selectedAuthor.length < 1) {
                    return;
                }

                // Clear the author's list before making the new search to
                // hide the existing list from the screen.
                this.authors = [];

                const AUTHORS_URL = APP_API + '/users';

                axios
                    .get(AUTHORS_URL, {
                        params: {
                            term: this.selectedAuthor
                        }
                    })
                    .then(response => {
                        this.authors = response.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 500),

            /**
             * Sets the supplied user as an author of the resume.
             *
             * @param   {Object} author
             *
             * @returns {void}
             */
            setAuthor(author) {
                this.$store.dispatch("updateAuthor", author);
            }
        },

        props: ['avatar'],

        watch: {
            selectedAuthor(value) {
                // Reset all the authors list to hide them from screen
                // whenever the user clears term in the input field.
                if (value.length < 1) {
                    this.authors = [];
                }
            }
        }
    };
</script>
