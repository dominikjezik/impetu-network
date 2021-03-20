<template>
    <header class="main-header">
        <div class="logo">
            <span>Impetu network</span>
        </div>
        <div class="search">
            <input type="text" class="input" placeholder="Search Impetu">
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
