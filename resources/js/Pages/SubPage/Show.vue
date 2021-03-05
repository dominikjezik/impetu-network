<template>
    <master-layout>
        <div class="container subpage-content">
            <div class="fake-nav"></div>
            <div class="timeline">
                <sub-page-hero :subpage="subpage" />
                <publish-new-post v-if="subpage.is_member" :subpage-name="subpage.name" />
                <div v-for="post in subpage.latest_posts">
                    <published-post :post="post" />
                </div>
            </div>
            <section class="meta">
                <div class="meta-item basic-informations">
                    <div class="header">Informations</div>
                    <div class="content">
                        <p class="description">{{ subpage.description }}</p>
                        <div class="members">
                            <div class="item">
                                <span class="number">{{ subpage.members_count }}</span>
                                <span class="label">Members</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";
import SubPageHero from "@/Components/SubPageHero";
import PublishedPost from "@/Components/PublishedPost";
import PublishNewPost from "@/Components/PublishNewPost";

export default {
    props: {
        subpage: Object
    },
    components: {
        MasterLayout,
        SubPageHero,
        PublishedPost,
        PublishNewPost
    },
    mounted() {
        console.log(this.subpage)
    }
}
</script>
