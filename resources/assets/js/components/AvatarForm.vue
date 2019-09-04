<template>
    <div>

        <div class="level">
            <img :src="avatar" width="50" height="50" class="mr-1">
            <h1>
                {{ user.name }}
                <small v-text="user.reputation"></small><small>XP</small>
            </h1>
        </div>


        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <!--action omitted to perform w/ JavaScript-->
            <!--{{ csrf_field() }}-->
            <!--csrf is omitted and done is bootstrap.js-->
            <image-upload name="avatar" @loaded="onLoad"></image-upload>

            <!--<button type="submit" class="btn btn-primary">Add Avatar</button>-->
            <!--this is omitted due to JS image-->
        </form>


    </div>
</template>


<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: {
            ImageUpload
        },

        data() {
            return {
                avatar: '/storage/' + this.user.avatar_path,
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
                // the user.id from the app.js, same as the props user.id?
                // returns a boolean
            }
        },

        methods: {
            onLoad(avatar) {
                //persist to server
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
                //
            }
        }
    }
</script>