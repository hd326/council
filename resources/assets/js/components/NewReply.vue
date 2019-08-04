<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <wysiwyg name="body" v-model="body" placeholder="Have something to say?" :shouldClear="completed" ref="trix"></wysiwyg>
                <!--<textarea 
                name="body" 
                id="body" 
                class="form-control" 
                placeholder="Have something to say?" 
                v-model="body"
                required rows="5">
            </textarea>-->
            </div>
            <button type="submit" class="btn btn-default" @click="addReply">Post</button>
        </div>

        <p class="text-center" v-else>Please <a href="/login">sign in</a> to participate in this
            discussion
        </p>
    </div>
</template>

<script>
import 'jquery.caret';
import 'at.js';

    export default {

        //props: ['endpoint'],
        data() {
            return {
                body: '',
                completed: false
            }
        },

        computed: {
            //ignedIn() {
            //   return window.App.signedIn;
            //
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    /*
                     If function is given, At.js will invoke it if local filter can not find any data
                     @param query [String] matched query
                     @param callback [Function] callback to render page.
                    */
                    remoteFilter: function (query, callback) {
                        //console.log('called');
                        $.getJSON("/api/users", {
                            name: query
                        }, function (usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {
                        body: this.body
                        //v-model="body"?
                        //i'm wondering why this doesn't need the much else
                        //i guess we assume the id, thread_id, and user_id already
                        //so if we post to this endpoint, this would mean we still
                        //post to the specific endpoint, as well as use it's function
                        //were basically using a function here to access a route
                        //and then using the data in the body here to send to the server 
                    })
                    .then(({data}) => {
                        this.body = '';
                        this.completed = true;
                        flash('Your reply has been posted.');
                        //this.$refs.trix.$refs.trix.value = '';
                        this.$emit('created', data);
                        //which event would be need to fire when we post a reply?
                    });
                // this is ES2015
            }
        }
    }
</script>