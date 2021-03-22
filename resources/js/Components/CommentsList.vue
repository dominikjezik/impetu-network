<template>
    <div class="comment" v-for="comment in comments">
        <div class="row">
            <img class="profile-img" :src="comment.comment_author.profile_photo_url" alt="">
            <a href="#" class="username">{{ comment.comment_author.name }}</a>
            <span class="time">{{ comment.created_at }}</span>
        </div>
        <div class="comment-body" v-html="comment.body" v-if="!comment.isEditBoxOpened"></div>
        <div class="comment-body" v-if="comment.isEditBoxOpened">
            <form class="add-new">
                <div class="row row-start ">
                    <div class="quill-container-comment">
                        <quill-editor :ref="`body-${comment.id}`" :specialId="specialId(comment)" :default-text="comment.body" />
                    </div>
                </div>
                <div class="row row-button">
                    <button type="submit" class="btn-primary-small" @click.prevent="submitUpdateComment(comment, $refs[`body-${comment.id}`].quill.root.innerHTML)">Comment</button>
                </div>
            </form>
        </div>
        <div class="options">
            <voting-buttons :voteable="comment" :endpoint="endpoint(comment)" />
            <button @click.prevent="switchIsDisplayedAddNewComment(comment)" class="comment-btn item">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                    <path d="M6,2h48c3.252,0,6,2.748,6,6v33c0,3.252-2.748,6-6,6H25.442L15.74,57.673C15.546,57.885,15.276,58,15,58
                        c-0.121,0-0.243-0.022-0.361-0.067C14.254,57.784,14,57.413,14,57V47H6c-3.252,0-6-2.748-6-6L0,8C0,4.748,2.748,2,6,2z"/>
                </svg>
                <span>Reply</span>
            </button>
            <div class="more">
                <button class="item item-three-dots" @click.prevent="switchContextMenu(comment)" :id="`btn-context-menu-comment-${comment.id}`" v-if="hasAtleastOneItem(comment)"></button>
                <ul class="context-menu" v-if="comment.isContextMenuOpened">
                    <li>
                        <button @click="comment.isEditBoxOpened = !comment.isEditBoxOpened" v-if="comment.can.update_comment">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
                                <g>
                                    <path d="M496.063,62.299l-46.396-46.4c-21.2-21.199-55.69-21.198-76.888,0l-18.16,18.161l123.284,123.294l18.16-18.161
                                        C517.311,117.944,517.314,83.55,496.063,62.299z"/>
                                </g>
                                <g>
                                    <path d="M22.012,376.747L0.251,494.268c-0.899,4.857,0.649,9.846,4.142,13.339c3.497,3.497,8.487,5.042,13.338,4.143
                                        l117.512-21.763L22.012,376.747z"/>
                                </g>
                                <g>
                                    <polygon points="333.407,55.274 38.198,350.506 161.482,473.799 456.691,178.568 		"/>
                                </g>
                            </svg>
                            Edit
                        </button>
                    </li>
                    <li>
                        <button @click.prevent="deleteComment(comment, comments)" v-if="comment.can.delete_comment">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
                            <path d="M294.111,256.001L504.109,46.003c10.523-10.524,10.523-27.586,0-38.109c-10.524-10.524-27.587-10.524-38.11,0L256,217.892
                                L46.002,7.894c-10.524-10.524-27.586-10.524-38.109,0s-10.524,27.586,0,38.109l209.998,209.998L7.893,465.999
                                c-10.524,10.524-10.524,27.586,0,38.109c10.524,10.524,27.586,10.523,38.109,0L256,294.11l209.997,209.998
                                c10.524,10.524,27.587,10.523,38.11,0c10.523-10.524,10.523-27.586,0-38.109L294.111,256.001z"/>
                            </svg>
                            Delete
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sub-comments">
            <publish-new-comment
                @newComment="newComment"
                :endpoint="endpoint(comment)"
                :original-comments="comment.comments_list"
                :commented-comment="comment"
                v-if="comment.isDisplayedAddNewComment" />
            <comments-list :comments="comment.comments_list" :post="post" @commentDeleted="deleteCommentFromUI" />
        </div>
    </div>
</template>

<script>
import PublishNewComment from "@/Components/PublishNewComment";
import VotingButtons from "@/Components/VotingButtons";
import QuillEditor from "@/Components/QuillEditor";
import Button from "@/Jetstream/Button";
import axios from "axios"

export default {
    components: {
        Button,
        QuillEditor,
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
        switchContextMenu(comment) {
            comment.isContextMenuOpened = !comment.isContextMenuOpened
        },
        switchIsDisplayedAddNewComment(comment) {
            comment.isDisplayedAddNewComment = !comment.isDisplayedAddNewComment
        },
        newComment({newComment, originalComments, commentedComment}) {
            commentedComment.isDisplayedAddNewComment = false
            originalComments.unshift(newComment)
        },
        endpoint(comment) {
            return `/r/${this.post.sub_page_name}/${this.post.id}/comments/${comment.id}`
        },
        specialId(comment) {
            return comment === undefined ? `0` : `${comment.id}e`
        },
        deleteComment(comment, originalComments) {
            axios.delete(`/r/${this.post.sub_page_name}/${this.post.id}/comments/${comment.id}`)
                .then(res => {
                    if(res.status === 200) {
                        this.$emit('commentDeleted', {
                            deletedComment: comment,
                            originalComments: originalComments
                        })
                        // this.comments = this.comments.filter(cmnt => cmnt.id !== comment.id)
                    }
                })
                .catch(err => console.log(err))
        },
        deleteCommentFromUI({deletedComment, originalComments}) {
            const index = originalComments.indexOf(deletedComment);
            if (index > -1) {
                originalComments.splice(index, 1);
            }
        },
        submitUpdateComment(comment, updatedBody) {
            axios.patch(`/r/${this.post.sub_page_name}/${this.post.id}/comments/${comment.id}`, {body: updatedBody})
                .then(res => {
                    if(res.status === 200) {
                        comment.body = updatedBody
                        comment.isEditBoxOpened = false
                    }
                })
                .catch(err => console.log(err))
        },
        hasAtleastOneItem(comment) {
            for (const ability in comment.can) {
                if(comment.can[ability])
                    return true
            }
            return false
        }
    },
    mounted() {
        document.addEventListener('click', (e) => {
            this.comments.forEach(comment => {
                if(e.originalTarget.id !== `btn-context-menu-comment-${comment.id}`) {
                    comment.isContextMenuOpened = false
                }
            })
        })
    }
}
</script>
