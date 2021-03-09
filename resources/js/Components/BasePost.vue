<template>
    <div class="content">
        <div class="post-header">
            <img class="profile-photo" :src="post.post_author.profile_photo_url" alt="">
            <span class="subpage-name" v-if="displaySubpageName">
                    <inertia-link :href="`/r/${post.sub_page_name}`">
                        {{ post.sub_page_name }}
                    </inertia-link>
                    <span class="dot">•</span>
                </span>
            <span class="author">
                    Posted by
                    <a href="#">{{ post.author.name }}</a>
                    <span class="dot">•</span>
                </span>
            <span class="created_at">{{ post.created_at }}</span>
        </div>

        <h3 class="title">{{ post.title }}</h3>
        <div class="body">
            {{ post.body }}
        </div>
    </div>
    <div class="post-footer">
        <voting-buttons :post="post" />
    </div>
</template>

<script>
import VotingButtons from "@/Components/VotingButtons";
export default {
    components: {
        VotingButtons
    },
    props: {
        post: Object,
        displaySubpageName: {
            type: Boolean,
            default: false
        }
    }
}
</script>

