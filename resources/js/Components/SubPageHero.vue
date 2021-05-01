<template>
    <section class="subpage-hero">
        <div class="cover" :style="`background-image: url('${ subpage.banner_url }')`"></div>
        <div class="subpage-hero-footer">
            <img :src="subpage.photo_url === null ? `/img/logo.png` : subpage.photo_url" alt="" class="logo">
            <div class="text-holder">
                <h1 class="title">{{ subpage.title }}</h1>
                <span class="name">r/{{ subpage.name }}</span>
            </div>
            <inertia-link :href="route('subpages.edit', subpage.name)" class="btn-primary btn-manage" v-if="subpage.can.manage_sub_page">Manage</inertia-link>
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
                this.$inertia.post(route('subpages.leave', this.subpage.name))
            } else {
                this.$inertia.post(route('subpages.join', this.subpage.name))
            }
        }
    }
}
</script>
