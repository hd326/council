<template>

<button :class="classes" @click="subscribe">Subscribe</button>

</template>

<script>

    export default {

        props: ['active'],

        //data() {
        //    return {
        //        active: false
        //    }
        //},

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default'];
            }
        },

        methods: {
            subscribe() {
                //let requestType = this.active ? 'delete' : 'post';
                axios[
                    (this.active ? 'delete' : 'post')
                    ](location.pathname + '/subscriptions');

                this.active = ! this.active;

                flash('Subscribed');

            },
        }
    }

</script>