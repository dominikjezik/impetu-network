<template>
    <div>
        <main-header />
        <main>
            <main-navigation />
            <div class="main-content">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script>
import MainHeader from "@/Components/MainHeader";
import MainNavigation from "@/Components/MainNavigation";

export default {
    components: {
        MainHeader,
        MainNavigation
    },

    data() {
        return {
            showingNavigationDropdown: false,
        }
    },

    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
    }
}
</script>
