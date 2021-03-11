<template>
    <master-layout>
        <div class="container container-home">
            <div class="fake-nav"></div>
            <div class="timeline">
                <div class="published-post show-post">
                    <base-post :post="post" />
                    <section class="comment-section">
                        <publish-new-comment @newComment="newComment" :endpoint="`/r/${this.post.sub_page_name}/${this.post.id}/comments`" />
                        <div class="comments">
                            <comments-list :comments="comments" :post="post" />
                        </div>
                    </section>
                </div>
            </div>
            <section class="meta">
                <div class="meta-item basic-informations">
                    <div class="header">Informations</div>
                    <div class="content">
                        <p class="description">lorem ipsum</p>
                        <div class="members">
                            <div class="item">
                                <span class="number">X</span>
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
import PublishedPost from "@/Components/PublishedPost";
import VotingButtons from "@/Components/VotingButtons";
import BasePost from "@/Components/BasePost";
import CommentsList from "@/Components/CommentsList";
import PublishNewComment from "@/Components/PublishNewComment";

export default {
    components: {
        CommentsList,
        BasePost,
        VotingButtons,
        PublishedPost,
        MasterLayout,
        PublishNewComment
    },
    props: {
        post: Object
    },
    data() {
        return {
            comments: this.post.comments_list,
        }
    },
    methods: {
        newComment({newComment}) {
            this.comments.unshift(newComment)
        }
    }
}
</script>
