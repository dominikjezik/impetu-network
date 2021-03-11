<template>
    <form class="add-new">
        <div class="row row-start">
            <img class="profile-img" :src="authUser.profile_photo_url" alt="">

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
</template>

<script>
import axios from "axios";

export default {
    props: {
        endpoint: String,
        originalComments: Array,
        commentedComment: Object
    },
    data() {
        return {
            form: {
                body: ""
            }
        }
    },
    computed: {
        authUser() {
            return this.$page.props.authUser
        }
    },
    methods: {
        reset(field) {
            delete this.$page.props.errors[field]
        },
        submit() {
            axios.post(this.endpoint, this.form)
                .then(res => this.addComment(res))
                .catch(err => console.log(err))
        },
        addComment(comment) {
            this.form.body = ""
            this.$emit('newComment', {
                newComment: comment.data,
                originalComments: this.originalComments,
                commentedComment: this.commentedComment
            })
        }
    }
}
</script>
