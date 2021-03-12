<template>
    <form class="add-new">
        <div class="row row-start">
            <img class="profile-img" :src="authUser.profile_photo_url" alt="">

            <div class="error-msg" v-if="$page.props.errors.body">{{ $page.props.errors.body }}</div>
            <div class="quill-container-comment">
                <quill-editor ref="body" :specialId="specialId" />
            </div>

        </div>
        <div class="row row-button">
            <button type="submit" class="btn-primary-small" @click.prevent="submit">Comment</button>
        </div>
    </form>
</template>

<script>
import QuillEditor from "@/Components/QuillEditor";
import axios from "axios";

export default {
    components: {
        QuillEditor
    },
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
        },
        specialId() {
            return this.commentedComment === undefined ? 0 : this.commentedComment.id
        }
    },
    methods: {
        reset(field) {
            delete this.$page.props.errors[field]
        },
        submit() {
            this.form.body = this.$refs.body.quill.root.innerHTML
            axios.post(this.endpoint, this.form)
                .then(res => this.addComment(res))
                .catch(err => console.log(err))
        },
        addComment(comment) {
            this.$refs.body.clear()
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
