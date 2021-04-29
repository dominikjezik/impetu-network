<template>
    <div>
        <main-header />
        <main>
            <main-navigation />
            <div class="main-content">
                <pop-up-notifications />
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script>
import MainHeader from "@/Components/MainHeader";
import MainNavigation from "@/Components/MainNavigation";
import PopUpNotifications from "@/Components/PopUpNotifications"

export default {
    components: {
        MainHeader,
        MainNavigation,
        PopUpNotifications
    },

    data() {
        return {
            showingNavigationDropdown: false
        }
    },

    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
    },
}
</script>

<style>

</style>
