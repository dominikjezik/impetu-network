<template>
    <section class="subpage-hero">
        <div class="cover"></div>
        <div class="subpage-hero-footer">
            <div class="logo"></div>
            <div class="text-holder">
                <h1 class="title">{{ subpage.title }}</h1>
                <span class="name">r/{{ subpage.name }}</span>
            </div>
            <inertia-link :href="`/r/${ subpage.name }/manage`" class="btn-primary btn-manage">Manage</inertia-link>
            <button class="btn-primary btn-join" :class="{ 'btn-outline': subpage.is_member }" @click.prevent="joinOrLeave">
                {{ subpage.is_member ? "Leave" : "Join" }}
            </button>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        subpage: Object
    },
    methods: {
        joinOrLeave() {
            if(this.subpage.is_member) {
                this.$inertia.post(`/r/${this.subpage.name}/leave`,)
            } else {
                this.$inertia.post(`/r/${this.subpage.name}/join`,)
            }
        }
    }
}
</script>
