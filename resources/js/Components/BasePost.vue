<template>
    <div class="content">
        <div class="post-header">
            <img class="profile-photo" :src="post.post_author.profile_photo_url" alt="">
            <span class="subpage-name" v-if="displaySubpageName">
                    <inertia-link :href="`/r/${post.sub_page_name}`">
                        {{ post.sub_page_name }}
                    </inertia-link>
                    <span class="dot">•</span>
                </span>
            <span class="author">
                    Posted by
                    <a href="#">{{ post.post_author.name }}</a>
                    <span class="dot">•</span>
                </span>
            <span class="created_at">{{ post.created_at }}</span>
        </div>

        <h3 class="title">{{ post.title }}</h3>
        <div class="body" v-html="post.body"></div>
    </div>
    <div class="post-footer">
        <voting-buttons :voteable="post" :endpoint="`/r/${post.sub_page_name}/${post.id}`" />
        <button class="item" :disabled="disableCommentButton">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                    <path d="M6,2h48c3.252,0,6,2.748,6,6v33c0,3.252-2.748,6-6,6H25.442L15.74,57.673C15.546,57.885,15.276,58,15,58
                        c-0.121,0-0.243-0.022-0.361-0.067C14.254,57.784,14,57.413,14,57V47H6c-3.252,0-6-2.748-6-6L0,8C0,4.748,2.748,2,6,2z"/>
                </svg>
            <span>{{ post.comment_count }}</span>
        </button>
        <button class="item share-btn" @click.prevent="savePost">
            <svg viewBox="0 -22 512 511" xmlns="http://www.w3.org/2000/svg">
                <path d="m512 233.820312-212.777344-233.320312v139.203125h-45.238281c-140.273437 0-253.984375 113.710937-253.984375 253.984375v73.769531l20.09375-22.019531c68.316406-74.851562 164.980469-117.5 266.324219-117.5h12.804687v139.203125zm0 0"/>
            </svg>
            <span>Share</span>
        </button>
        <button class="item" @click.prevent="savePost">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 212.045 212.045" style="enable-background:new 0 0 212.045 212.045;" xml:space="preserve">
            <path d="M167.871,0H44.84C34.82,0,26.022,8.243,26.022,18v182c0,3.266,0.909,5.988,2.374,8.091c1.752,2.514,4.573,3.955,7.598,3.954
                c2.86,0,5.905-1.273,8.717-3.675l55.044-46.735c1.7-1.452,4.142-2.284,6.681-2.284c2.538,0,4.975,0.832,6.68,2.288l54.86,46.724
                c2.822,2.409,5.657,3.683,8.512,3.683c4.828,0,9.534-3.724,9.534-12.045V18C186.022,8.243,177.891,0,167.871,0z"/>
            </svg>
            <span>Save</span>
        </button>
    </div>
</template>

<script>
import VotingButtons from "@/Components/VotingButtons";
export default {
    components: {
        VotingButtons
    },
    props: {
        post: Object,
        displaySubpageName: {
            type: Boolean,
            default: false
        },
        disableCommentButton: Boolean
    },
    methods: {
        savePost() {

        }
    }
}
</script>

