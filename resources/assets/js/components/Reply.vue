<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success': 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+reply.owner.name" 
                        v-text="reply.owner.name">
                    </a> said <span v-text="ago"></span> 
                </h5>

                <!--@if (Auth::check())
                <div>
                <favorite :reply="{{ $reply }}"></favorite>
                    {{--<form method="POST" action="/replies/{{ $reply->id }}/favorites">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : "" }}>
                            {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                        </button>
                    </form>--}}
                </div>
                @endif-->

                <div v-if="signedIn">
                <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <form @submit="update">
                <!--<textarea class="form-control" v-model="body" required></textarea>-->
                <wysiwyg v-model="body"></wysiwyg>
                <button class="btn btn-xs btn-link">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body">
                <!--{{--<div class="body">{{ $reply->body }}</div>--}}-->
            </div>

        </div>

        <!--@can('update', $reply)-->

        <div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <!--<div v-if="canUpdate">-->
            <div v-if="authorize('owns', reply)">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-if="authorize('updateThread', reply.thread)">Best Reply?</button>
            <!--<form method="POST" action="/replies/{{$reply->id}}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
            </form>-->
        </div>

        <!--@endcan-->
    </div>
</template>

<script>
import Favorite from './Favorite.vue';
import moment from 'moment';

    export default {
        props: ['reply'],

        components: { Favorite }, 

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
                //reply: this.data
            };
        },

        computed: {
            ago() {
                return moment.utc(this.reply.created_at).fromNow() + '...';
            },
            
            //signedIn () {
            //    return window.App.signedIn
            //},

            //canUpdate() {
            //    return this.authorize(user => this.data.user_id == user.id);
            //    //this takes the data.user_id, and matches with w/ user.id
            //    //return data.user_id == window.App.user.id;
            //}
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.reply.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });
        //whats up with this update method
                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.reply.id);
                this.$emit('deleted', this.reply.id);
                //when deleted... what is going to do on the parent?
                //an announcement of deletion
                //$(this.$el).fadeOut(300, () => {
                //flash('Your reply has been deleted');
                //});  
            },

            markBestReply() {
                axios.post('/replies/' + this.reply.id + '/best');
                window.events.$emit('best-reply-selected', this.reply.id);
            }
        }
    }
</script>