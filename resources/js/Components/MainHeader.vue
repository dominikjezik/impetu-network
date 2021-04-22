<template>
    <header class="main-header">
        <div class="logo">
            <inertia-link href="/">
                <img src="/img/logo.png" alt="">
                <span>Impetu network</span>
            </inertia-link>
        </div>
        <div class="options">
            <inertia-link href="/" class="user">
                <img :src="this.$page.props.user.profile_photo_url" />
                <span>{{ this.$page.props.user.name }}</span>
            </inertia-link>

        </div>
    </header>
</template>

<script>
export default {

}
</script>
