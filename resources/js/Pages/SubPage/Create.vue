<template>
    <master-layout>
        <div class="container container-create-community">
            <div class="fake-nav"></div>
            <form class="post-container">
                <h2>Create a new community</h2>

                <div class="field">
                    <div class="error-msg" v-if="$page.props.errors.name">{{ $page.props.errors.name }}</div>
                    <input
                        type="text"
                        class="input"
                        placeholder="Name"
                        v-model="form.name"
                        @keydown="reset('name')"
                        :class="{ 'input-error' : $page.props.errors.name }">
                </div>

                <div class="field">
                    <div class="error-msg" v-if="$page.props.errors.title">{{ $page.props.errors.title }}</div>
                    <input
                        type="text"
                        class="input"
                        placeholder="Title"
                        v-model="form.title"
                        @keydown="reset('title')"
                        :class="{ 'input-error' : $page.props.errors.title }">
                </div>

                <div class="field">
                    <div class="error-msg" v-if="$page.props.errors.description">{{ $page.props.errors.description }}</div>
                    <textarea
                        cols="30" rows="10" class="input"
                        placeholder="Description"
                        v-model="form.description"
                        @keydown="reset('description')"
                        :class="{ 'input-error' : $page.props.errors.description  }"
                    ></textarea>
                </div>

                <button type="submit" class="btn-primary" @click.prevent="submit">Create</button>
            </form>
            <section class="meta"></section>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";

export default {
    components: {
        MasterLayout
    },
    data() {
        return {
            form: {
                name: '',
                title: '',
                description: ''
            }
        }
    },
    methods: {
        submit() {
            this.$inertia.post(`/create-community`, this.form)
        },
        reset(field) {
            delete this.$page.props.errors[field]
        }
    }
}
</script>
