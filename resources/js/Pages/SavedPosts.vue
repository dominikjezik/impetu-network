<template>
    <master-layout>
        <div class="container container-home">
            <div class="fake-nav"></div>
            <div class="timeline">
                <h1>Saved posts</h1>
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
import PublishedPost from "@/Components/PublishedPost";

export default {
    components: {
        MasterLayout,
        PublishedPost
    },
    props: {
        posts: Array
    }
}
</script>
