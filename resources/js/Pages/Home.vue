<template>
    <master-layout>
        <div class="container container-home">
            <div class="fake-nav"></div>
            <div class="timeline">
                <publish-new-post />
                <div v-for="post in posts">
                    <published-post :post="post" :display-subpage-name="true" />
                </div>
            </div>
            <section class="meta"></section>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";
import PublishNewPost from "@/Components/PublishNewPost";
import PublishedPost from "@/Components/PublishedPost";

export default {
    components: {
        MasterLayout,
        PublishNewPost,
        PublishedPost
    },
    props: {
        posts: Array
    }
}
</script>

