<template>
    <div class="voting-buttons">
        <button class="item btn-vote btn-vote-up" @click.prevent="upvote" :class="{ 'btn-vote-up-clicked' : isUpvoted}">
            <svg width="410" height="492" viewBox="0 0 410 492" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M243.582 464.344L243.414 465.112L243.414 135.888L347.138 239.384C352.202 244.452 359.062 247.232 366.262 247.232C373.462 247.232 380.274 244.452 385.35 239.384L401.462 223.28C406.526 218.216 409.326 211.468 409.326 204.272C409.326 197.072 406.546 190.32 401.482 185.256L224.086 7.84398C219.002 2.75998 212.23 -0.0200235 205.026 -2.38351e-05C197.782 -0.0200242 191.006 2.75998 185.93 7.84398L8.51801 185.256C3.45801 190.32 0.674018 197.068 0.674018 204.272C0.674017 211.468 3.46201 218.216 8.51801 223.28L24.63 239.384C29.686 244.452 36.438 247.232 43.638 247.232C50.834 247.232 57.23 244.452 62.29 239.384L166.594 134.72L166.594 464.712C166.594 479.54 179.374 492 194.194 492L216.982 492C231.802 492 243.582 479.172 243.582 464.344Z" fill="black"/>
            </svg>
        </button>
        <span class="score">{{ post.score }}</span>
        <button class="item btn-vote btn-vote-down" @click.prevent="downvote" :class="{ 'btn-vote-down-clicked' : isDownvoted}">
            <svg width="410" height="492" viewBox="0 0 410 492" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M166.418 27.6559L166.586 26.8879L166.586 356.112L62.862 252.616C57.798 247.548 50.938 244.768 43.738 244.768C36.538 244.768 29.726 247.548 24.65 252.616L8.53801 268.72C3.47401 273.784 0.674014 280.532 0.674014 287.728C0.674014 294.928 3.45402 301.68 8.51802 306.744L185.914 484.156C190.998 489.24 197.77 492.02 204.974 492C212.218 492.02 218.994 489.24 224.07 484.156L401.482 306.744C406.542 301.68 409.326 294.932 409.326 287.728C409.326 280.532 406.538 273.784 401.482 268.72L385.37 252.616C380.314 247.548 373.562 244.768 366.362 244.768C359.166 244.768 352.77 247.548 347.71 252.616L243.406 357.28L243.406 27.2879C243.406 12.4599 230.626 -0.000119328 215.806 -0.000119505L193.018 -0.000119777C178.198 -0.000119953 166.418 12.8279 166.418 27.6559Z" fill="black"/>
            </svg>
        </button>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        post: Object
    },
    computed: {
        isUpvoted() {
            return this.post.voted_by_user === null ? false : this.post.voted_by_user.upvote === 1
        },
        isDownvoted() {
            return this.post.voted_by_user === null ? false : this.post.voted_by_user.upvote === 0
        }
    },
    methods: {
        upvote() {
            axios.post(`/r/${this.post.sub_page_name}/${this.post.id}/upvote`)
                .then(res => { this.updateVote(true) })
                .catch(err => console.log(err))
        },
        downvote() {
            axios.post(`/r/${this.post.sub_page_name}/${this.post.id}/downvote`)
                .then(res => { this.updateVote(false) })
                .catch(err => console.log(err))
        },
        updateVote(upvote) {
            if(this.post.voted_by_user == null) {
                this.post.voted_by_user = {
                    upvote: null
                }
            }
            if(upvote) {
                if(this.post.voted_by_user.upvote === 1) {
                    this.post.voted_by_user.upvote = null
                    this.post.score--
                } else {


                    if(this.post.voted_by_user.upvote === 0) {
                        this.post.score += 2
                    }
                    else {
                        this.post.score++
                    }
                    this.post.voted_by_user.upvote = 1
                }
            } else {
                if(this.post.voted_by_user.upvote === 0) {
                    this.post.voted_by_user.upvote = null
                    this.post.score++
                } else {


                    if(this.post.voted_by_user.upvote === 1) {
                        this.post.score -= 2
                    } else {
                        this.post.score--
                    }
                    this.post.voted_by_user.upvote = 0
                }
            }
        }
    }
}
</script>
