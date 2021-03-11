<template>
    <div class="comment" v-for="comment in comments">
        <div class="row">
            <img class="profile-img" :src="comment.comment_author.profile_photo_url" alt="">
            <a href="#" class="username">{{ comment.comment_author.name }}</a>
            <span class="time">{{ comment.created_at }}</span>
        </div>
        <p class="comment-body">{{ comment.body }}</p>
        <div class="options">
            <voting-buttons :voteable="comment" :endpoint="endpoint(comment)" />
            <button @click.prevent="switchIsDisplayedAddNewComment(comment)" class="comment-btn">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                    <path d="M6,2h48c3.252,0,6,2.748,6,6v33c0,3.252-2.748,6-6,6H25.442L15.74,57.673C15.546,57.885,15.276,58,15,58
                        c-0.121,0-0.243-0.022-0.361-0.067C14.254,57.784,14,57.413,14,57V47H6c-3.252,0-6-2.748-6-6L0,8C0,4.748,2.748,2,6,2z"/>
                </svg>
                <span>Reply</span>
            </button>
        </div>
        <div class="sub-comments">
            <publish-new-comment
                @newComment="newComment"
                :endpoint="endpoint(comment)"
                :original-comments="comment.comments_list"
                :commented-comment="comment"
                v-if="comment.isDisplayedAddNewComment" />
            <comments-list :comments="comment.comments_list" :post="post" />
        </div>
    </div>
</template>

<script>
import PublishNewComment from "@/Components/PublishNewComment";
import VotingButtons from "@/Components/VotingButtons";

export default {
    components: {
        VotingButtons,
        PublishNewComment
    },
    props: {
        comments: Array,
        post: Object
    },
    data() {
        return {
            isDisplayedAddNewComment: false
        }
    },
    methods: {
        switchIsDisplayedAddNewComment(comment) {
            comment.isDisplayedAddNewComment = !comment.isDisplayedAddNewComment
        },
        newComment({newComment, originalComments, commentedComment}) {
            commentedComment.isDisplayedAddNewComment = false
            originalComments.unshift(newComment)
        },
        endpoint(comment) {
            return `/r/${this.post.sub_page_name}/${this.post.id}/comments/${comment.id}`
        }
    }
}
</script>
