<script>

import Replies from '../components/Replies.vue';
import SubscribeButton from '../components/SubscribeButton.vue';

    export default {
        //props: ['dataRepliesCount', 'dataLocked'],
        props: ['thread'],
        components: { Replies, SubscribeButton },

        data () {
            return {
                //repliesCount: this.initialRepliesCount,
                //locked: this.dataLocked
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                pinned: this.thread.pinned,
                title: this.thread.title,
                body: this.thread.body,
                form: {
                    title: this.thread.title,
                    body: this.thread.body
                },
                editing: false,
            };
        },

        methods: {
            toggleLock () {
                let uri = `/locked-threads/` + this.thread.slug;
                axios[this.locked ? 'delete' : 'post'](uri);
                
                this.locked = ! this.locked;
            },

            togglePin () {
                let uri = `/pinned-threads/` + this.thread.slug;
                axios[this.pinned ? 'delete' : 'post'](uri);
                
                this.pinned = ! this.pinned;
            },

            update () {
                //axios
                // /threads/channel/thread-slug
                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
                axios.patch(uri, this.form).then(() => {
                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;
                    ('Your thread has been updated.');
                })
            },

            resetForm () {
                this.form = {
                    title:this.thread.title,
                    body:this.thread.body,
                };
                this.editing = false;
            },

            classes(target){
                return [
                    'btn',
                    target ? 'btn-primary' : 'btn-default'
                ];
            }
            
        }
    }
 
</script>