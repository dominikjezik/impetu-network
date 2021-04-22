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
            <section class="meta">
<!--                <div class="meta-item basic-informations">-->
<!--                    <div class="header">Informations</div>-->
<!--                    <div class="content">-->
<!--                        <p class="description">lorem ipsum</p>-->
<!--                        <div class="members">-->
<!--                            <div class="item">-->
<!--                                <span class="number">X</span>-->
<!--                                <span class="label">Members</span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </section>
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
    },
    mounted() {
        console.log(this.posts)
    }
}
</script>

