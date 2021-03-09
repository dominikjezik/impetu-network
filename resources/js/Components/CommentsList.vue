<template>
    <div class="comment" v-for="comment in comments">
        <div class="row">
            <img class="profile-img" :src="comment.comment_author.profile_photo_url" alt="">
            <a href="#" class="username">{{ comment.comment_author.name }}</a>
            <span class="time">{{ comment.created_at }}</span>
        </div>
        <p class="body">{{ comment.body }}</p>
        <div class="sub-comments">
            <comments-list :comments="comment.comments_list" />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        comments: Array
    },
    mounted() {
        console.log(this.comments)
    }
}
</script>
