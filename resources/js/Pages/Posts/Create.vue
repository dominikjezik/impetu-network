<template>
    <master-layout>
        <div class="container container-add-post">
            <div class="fake-nav"></div>
            <form class="post-container publish-post-container">
                <h2>Create a new post</h2>

                <select v-model="community" name="community" class="select-box select-box-community">
                    <option v-for="item in list_of_communities" :value="item.name">{{ item.title }} r/{{ item.name }}</option>
                </select>

                <div class="error-msg" v-if="$page.props.errors.title">{{ $page.props.errors.title }}</div>
                <input type="text" name="title" class="input" placeholder="Title" v-model="form.title"
                       @keydown="reset('title')" :class="{ 'input-error' : $page.props.errors.title  }">

                <div class="error-msg" v-if="$page.props.errors.body">{{ $page.props.errors.body }}</div>
                <textarea name="body" id=""
                      cols="30" rows="10" class="input" placeholder="Body"
                      v-model="form.body" @keydown="reset('body')"
                      :class="{ 'input-error' : $page.props.errors.body  }"
                ></textarea>

                <button type="submit" class="btn-primary" @click.prevent="submit">Publish</button>
            </form>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";

export default {
    components: { MasterLayout },
    props: {
        subpage: Object,
        list_of_communities: Array
    },
    data() {
        return {
            form: {
                title: "",
                body: ""
            },
            community: this.getDefaultCommunity()
        }
    },
    methods: {
        submit() {
            this.$inertia.post(`/r/${this.community}/posts`, this.form)
        },
        reset(field) {
            delete this.$page.props.errors[field]
        },
        getDefaultCommunity() {
            if(this.subpage === undefined) {
                if(this.list_of_communities.length > 0)
                    return this.list_of_communities[0].name
                else
                    return ""


            } else {
                return this.subpage.name
            }
        }
    }
}
</script>

