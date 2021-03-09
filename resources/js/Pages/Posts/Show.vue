<template>
    <master-layout>
        <div class="container container-home">
            <div class="fake-nav"></div>
            <div class="timeline">
                <div class="published-post show-post">
                    <base-post :post="post" />
                    <section class="comment-section">
                        <form class="add-new">
                            <div class="row">
                                <img class="profile-img" :src="post.post_author.profile_photo_url" alt="">

                                <div class="error-msg" v-if="$page.props.errors.body">{{ $page.props.errors.body }}</div>
                                <textarea name="body" id=""
                                          cols="30" rows="5" class="input body" placeholder="Body"
                                          :class="{ 'input-error' : $page.props.errors.body  }"
                                          v-model="form.body" @keydown="reset('body')"
                                ></textarea>
                            </div>
                            <div class="row row-button">
                                <button type="submit" class="btn-primary-small" @click.prevent="submit">Comment</button>
                            </div>
                        </form>
                        <div class="comments">
                            <comments-list :comments="comments" />
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
import axios from "axios";

export default {
    components: {
        CommentsList,
        BasePost,
        VotingButtons,
        PublishedPost,
        MasterLayout
    },
    props: {
        post: Object
    },
    data() {
        return {
            comments: this.post.comments_list,
            form: {
                body: ""
            }
        }
    },
    methods: {
        reset(field) {
            delete this.$page.props.errors[field]
        },
        submit() {
            axios.post(`/r/${this.post.sub_page_name}/${this.post.id}/comments`, this.form)
                .then(res => this.addComment(res))
                .catch(err => console.log(err))
        },
        addComment(comment) {
            this.form.body = ""
            this.comments.unshift(comment.data)
        }
    }
}
</script>
