<template>
    <div class="published-post-new">
        <img class="profile-img" src="/img/profile1.jpg" alt="">
        <input type="text" class="input" placeholder="Create post" @focus="displayCreatePostForm">
    </div>
</template>

<script>
export default {
    props: {
        subpageName: String
    },
    methods: {
        displayCreatePostForm() {
            if(this.subpageName !== undefined)
                this.$inertia.get(`/r/${this.subpageName}/publish`)
            else
                this.$inertia.get(`/publish`)
        }
    }
}
</script>
