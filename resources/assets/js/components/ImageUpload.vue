<template>
<input type="file" accept="image/*" @change="onChange">
</template>

<script>
export default {
    methods : {
    onChange(e) {

        if (! e.target.files.length) return;
        let avatar = e.target.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(avatar);
        reader.onload = e => {
            //console.log(e);
            let src = e.target.result;
            //this.$emit('loaded', {
            //    src: e.target.result,
            //    file: avatar
            //});
            this.$emit('loaded', { src, avatar });
            //this.avatar = e.target.result;
        };
        }
    }
}
</script>