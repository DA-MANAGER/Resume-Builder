<template>
    <div class="form-bullet-list">
        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('add-option')">Write your own options (Optional)</label>

                <p class="text-muted card-text">Write each individual bullet point, then click the "Save and Add
                    Another" button.</p>
            </div>

            <div class="col-sm-8">
                <textarea class="form-control" placeholder="Enter option..."
                          v-bind:id="getHashedElementId('add-option')"
                          v-model="text"></textarea>

                <div class="text-right py-3">
                    <button type="button" class="btn btn-light shadow-sm" aria-pressed="false"
                            v-on:click="addItem(text); text = ''">Save and Add Another
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-4">
                <label class="col-form-label font-weight-bold"
                       v-bind:for="getHashedElementId('review-options')">Review your options</label>
            </div>

            <div class="col-sm-8">
                <ul class="list-group"
                    v-if="formData.items.length > 0">
                    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                        v-for="(item, index) in formData.items"
                        v-bind:index="index"
                        v-bind:key="index">
                        <span v-text="item"></span>

                        <button type="button" class="close ml-3" aria-label="Close"
                                v-on:click="removeItem(index)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </li>
                </ul>

                <div class="alert alert-warning" role="alert"
                     v-else>
                    No option is added yet. Please type something and click on<br><strong>"Save and Add
                    Another"</strong> button to add one.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ComponentHashMixin from "./../../mixins/ComponentHashMixin.js";
    import HandleFormDataMixin from "./../../mixins/HandleFormDataMixin.js";

    export default {
        data() {
            return {
                formData: {
                    items: []
                },
                text: ""
            };
        },

        methods: {
            /**
             * Adds a new item to the list.
             * 
             * @param   {String} item
             * 
             * @returns {void}
             */
            addItem(item) {
                if (item.length === 0) {
                    return;
                }

                this.formData.items.push(item);
            },

            /**
             * Removes the item from the list by the supplied index.
             *
             * @param   {Number} index
             *
             * @returns {void}
             */
            removeItem(index) {
                this.formData.items.splice(index, 1);
            }
        },

        mixins: [
            ComponentHashMixin,
            HandleFormDataMixin
        ]
    };
</script>
