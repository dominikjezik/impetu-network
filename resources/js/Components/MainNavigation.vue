<template>
    <nav class="main-navigation">
        <ul>
            <li>
                <inertia-link href="/" :class="{ 'selected': isSelected('') }">Home</inertia-link>
            </li>
            <li>
                <inertia-link href="#popular">Popular</inertia-link>
            </li>
            <li>
                <inertia-link href="/publish">Create a post</inertia-link>
            </li>
            <li>
                <inertia-link href="#newcomunity">Create a community</inertia-link>
            </li>
            <li>
                <inertia-link href="/r/test" :class="{ 'selected': isSelected('r/test') }">Test sub page</inertia-link>
            </li>
        </ul>

    </nav>
</template>

<script>
export default {
    methods: {
        isSelected(item) {
            return location.pathname.substr(1) === item;
        }
    },

}
</script>
