<template>
    <div class="notifications-window">
        <h2 class="main-title">Notifications</h2>
        <ul class="notifications">
            <li v-for="notification in notifications">
                <inertia-link href="#action">
                    <div class="icon">
                        <div class="comment-upvote" v-if="notificationType(notification.type) === `NewCommentVote`">
                            <img class="main-image" :src="notification.data.image" alt="">
                            <div class="sub" :class="{'sub-negative' : notification.data.upvote === false}">
                                <svg v-if="notification.data.upvote" class="vote-arrow-svg" viewBox="0 0 410 492" xmlns="http://www.w3.org/2000/svg"><path d="M243.582 464.344L243.414 465.112L243.414 135.888L347.138 239.384C352.202 244.452 359.062 247.232 366.262 247.232C373.462 247.232 380.274 244.452 385.35 239.384L401.462 223.28C406.526 218.216 409.326 211.468 409.326 204.272C409.326 197.072 406.546 190.32 401.482 185.256L224.086 7.84398C219.002 2.75998 212.23 -0.0200235 205.026 -2.38351e-05C197.782 -0.0200242 191.006 2.75998 185.93 7.84398L8.51801 185.256C3.45801 190.32 0.674018 197.068 0.674018 204.272C0.674017 211.468 3.46201 218.216 8.51801 223.28L24.63 239.384C29.686 244.452 36.438 247.232 43.638 247.232C50.834 247.232 57.23 244.452 62.29 239.384L166.594 134.72L166.594 464.712C166.594 479.54 179.374 492 194.194 492L216.982 492C231.802 492 243.582 479.172 243.582 464.344Z" fill="black"/></svg>
                                <svg v-else class="vote-arrow-svg" viewBox="0 0 410 492" xmlns="http://www.w3.org/2000/svg"><path d="M166.418 27.6559L166.586 26.8879L166.586 356.112L62.862 252.616C57.798 247.548 50.938 244.768 43.738 244.768C36.538 244.768 29.726 247.548 24.65 252.616L8.53801 268.72C3.47401 273.784 0.674014 280.532 0.674014 287.728C0.674014 294.928 3.45402 301.68 8.51802 306.744L185.914 484.156C190.998 489.24 197.77 492.02 204.974 492C212.218 492.02 218.994 489.24 224.07 484.156L401.482 306.744C406.542 301.68 409.326 294.932 409.326 287.728C409.326 280.532 406.538 273.784 401.482 268.72L385.37 252.616C380.314 247.548 373.562 244.768 366.362 244.768C359.166 244.768 352.77 247.548 347.71 252.616L243.406 357.28L243.406 27.2879C243.406 12.4599 230.626 -0.000119328 215.806 -0.000119505L193.018 -0.000119777C178.198 -0.000119953 166.418 12.8279 166.418 27.6559Z" fill="black"/></svg>
                            </div>
                        </div>
                        <div class="comment" v-else-if="notificationType(notification.type) === `NewComment`">
                            <img class="main-image" :src="notification.data.image" alt="">
                            <div class="sub">
                                <svg class="comment-svg" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 60 60" xml:space="preserve"> <path d="M6,2h48c3.252,0,6,2.748,6,6v33c0,3.252-2.748,6-6,6H25.442L15.74,57.673C15.546,57.885,15.276,58,15,58 c-0.121,0-0.243-0.022-0.361-0.067C14.254,57.784,14,57.413,14,57V47H6c-3.252,0-6-2.748-6-6L0,8C0,4.748,2.748,2,6,2z"/> </svg>
                            </div>
                        </div>
                        <div class="post-upvote" v-else-if="notificationType(notification.type) === `NewPostVote`">
                            <img class="main-image" :src="notification.data.image" alt="">
                            <div class="sub" :class="{'sub-negative' : notification.data.upvote === false}">
                                <svg v-if="notification.data.upvote" class="vote-arrow-svg" viewBox="0 0 410 492" xmlns="http://www.w3.org/2000/svg"><path d="M243.582 464.344L243.414 465.112L243.414 135.888L347.138 239.384C352.202 244.452 359.062 247.232 366.262 247.232C373.462 247.232 380.274 244.452 385.35 239.384L401.462 223.28C406.526 218.216 409.326 211.468 409.326 204.272C409.326 197.072 406.546 190.32 401.482 185.256L224.086 7.84398C219.002 2.75998 212.23 -0.0200235 205.026 -2.38351e-05C197.782 -0.0200242 191.006 2.75998 185.93 7.84398L8.51801 185.256C3.45801 190.32 0.674018 197.068 0.674018 204.272C0.674017 211.468 3.46201 218.216 8.51801 223.28L24.63 239.384C29.686 244.452 36.438 247.232 43.638 247.232C50.834 247.232 57.23 244.452 62.29 239.384L166.594 134.72L166.594 464.712C166.594 479.54 179.374 492 194.194 492L216.982 492C231.802 492 243.582 479.172 243.582 464.344Z" fill="black"/></svg>
                                <svg v-else class="vote-arrow-svg" viewBox="0 0 410 492" xmlns="http://www.w3.org/2000/svg"><path d="M166.418 27.6559L166.586 26.8879L166.586 356.112L62.862 252.616C57.798 247.548 50.938 244.768 43.738 244.768C36.538 244.768 29.726 247.548 24.65 252.616L8.53801 268.72C3.47401 273.784 0.674014 280.532 0.674014 287.728C0.674014 294.928 3.45402 301.68 8.51802 306.744L185.914 484.156C190.998 489.24 197.77 492.02 204.974 492C212.218 492.02 218.994 489.24 224.07 484.156L401.482 306.744C406.542 301.68 409.326 294.932 409.326 287.728C409.326 280.532 406.538 273.784 401.482 268.72L385.37 252.616C380.314 247.548 373.562 244.768 366.362 244.768C359.166 244.768 352.77 247.548 347.71 252.616L243.406 357.28L243.406 27.2879C243.406 12.4599 230.626 -0.000119328 215.806 -0.000119505L193.018 -0.000119777C178.198 -0.000119953 166.418 12.8279 166.418 27.6559Z" fill="black"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="description">
                        <p class="text" v-if="notificationType(notification.type) === `NewPostVote`">
                            <b>{{ notification.data.user }}</b> {{ notification.data.upvote ? "up" : "down"}} voted your post: "{{ notification.data.title }}"
                        </p>
                        <p class="text" v-else-if="notificationType(notification.type) === `NewComment`">
                            <b>{{ notification.data.user }}</b> commented on your comment in <b>{{ notification.data.subpage }}</b>.
                        </p>
                        <p class="text" v-else-if="notificationType(notification.type) === `NewCommentVote`">
                            <b>{{ notification.data.user }}</b> {{ notification.data.upvote ? "up" : "down"}} voted your comment: "{{ notification.data.body }}"
                        </p>
                        <span class="time">{{ getFormattedTime(notification.updated_at) }}</span>
                    </div>
                </inertia-link>
            </li>
        </ul>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    methods: {
        notificationType(fullname) {
            return fullname.split("\\").pop()
        },
        getFormattedTime(raw) {
            return moment(raw).fromNow()
        }
    },
    data() {
        return {
            notifications: []
        }
    },
    mounted() {
        axios.get(route("notifications.index"))
            .then(res => this.notifications = res.data)
            .catch(err => this.emitter.emit("notify", { type: "error", payload: "Oops! An error occurred." }))
    }
}
</script>
