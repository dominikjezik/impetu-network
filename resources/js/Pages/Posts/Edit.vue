<template>
    <master-layout>
        <div class="container container-add-post">
            <div class="fake-nav"></div>
            <form class="post-container publish-post-container">
                <h2>Update post</h2>

                <div class="error-msg" v-if="$page.props.errors.title">{{ $page.props.errors.title }}</div>
                <input type="text" name="title" class="input" placeholder="Title" v-model="form.title"
                       @keydown="reset('title')" :class="{ 'input-error' : $page.props.errors.title  }">

                <div class="error-msg" v-if="$page.props.errors.body">{{ $page.props.errors.body }}</div>
                <div class="quill-container-post">
                    <quill-editor ref="body" :default-text="post.body" />
                </div>

                <button type="submit" class="btn-primary" @click.prevent="submit">Update</button>
            </form>
            <section class="meta"></section>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";
import QuillEditor from "@/Components/QuillEditor";

export default {
    components: {
        MasterLayout,
        QuillEditor
    },
    props: {
        post: Object
    },
    data() {
        return {
            form: {
                title: this.post.title,
                body: ""
            }
        }
    },
    methods: {
        submit() {
            this.form.body = this.$refs.body.quill.root.innerHTML
            this.$inertia.patch(route('posts.update', [this.post.sub_page.name, this.post.id]), this.form)
        },
        reset(field) {
            delete this.$page.props.errors[field]
        }
    }
}
</script>

