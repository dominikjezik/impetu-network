<template>
    <inertia-link :href="route('posts.show', [ post.sub_page_name, post.id ])" class="published-post">
        <base-post :post="post" :display-subpage-name="displaySubpageName" />
    </inertia-link>
</template>

<script>
import VotingButtons from "@/Components/VotingButtons";
import BasePost from "@/Components/BasePost";

export default {
    components: {
        BasePost,
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
