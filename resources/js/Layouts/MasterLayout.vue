<template>
    <div>
        <main-header />
        <main>
            <main-navigation />
            <div class="main-content">
                <ul class="pop-up-notifications">
                    <transition-group name="list">
                        <li class="pop-up-notification display-pop-up-notification" v-for="(notification, i) in notifications">
                            <span class="title">{{ notification.payload }}</span>
                            <button @click="closeNotification(i)">x</button>
                        </li>
                    </transition-group>
                </ul>
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
            notifications: [],
        }
    },

    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
        closeNotification(position) {
            this.notifications.splice(position, 1)
        }
    },

    mounted() {
        this.emitter.on("notify", arg => {
            this.notifications.push(arg)
        });
    }
}
</script>

<style>
.pop-up-notification.list-enter-active, .pop-up-notification.list-leave-active {
    transition: all 1s;
}
.pop-up-notification.list-enter, .pop-up-notification.list-leave-to /* .list-leave-active below version 2.1.8 */ {
    opacity: 0;
    transform: translateY(30px);
}
</style>
