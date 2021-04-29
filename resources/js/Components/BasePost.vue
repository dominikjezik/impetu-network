<template>
    <div class="content">
        <div class="post-header">
            <img class="profile-photo" :src="post.post_author.profile_photo_url" alt="">
            <span class="subpage-name" v-if="displaySubpageName">
                    <inertia-link :href="route('subpages.show', post.sub_page_name)">
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
        <voting-buttons :voteable="post" :endpoint="route('posts.show', [post.sub_page_name, post.id])" />
        <button class="item" :disabled="disableCommentButton">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve"> <path d="M6,2h48c3.252,0,6,2.748,6,6v33c0,3.252-2.748,6-6,6H25.442L15.74,57.673C15.546,57.885,15.276,58,15,58 c-0.121,0-0.243-0.022-0.361-0.067C14.254,57.784,14,57.413,14,57V47H6c-3.252,0-6-2.748-6-6L0,8C0,4.748,2.748,2,6,2z"/> </svg>
            <span>{{ post.comment_count }}</span>
        </button>
        <button class="item share-btn" @click.prevent="sharePost">
            <svg viewBox="0 -22 512 511" xmlns="http://www.w3.org/2000/svg"> <path d="m512 233.820312-212.777344-233.320312v139.203125h-45.238281c-140.273437 0-253.984375 113.710937-253.984375 253.984375v73.769531l20.09375-22.019531c68.316406-74.851562 164.980469-117.5 266.324219-117.5h12.804687v139.203125zm0 0"/> </svg>
            <span>Share</span>
        </button>
        <button class="item" @click.prevent="savePost">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 212.045 212.045" style="enable-background:new 0 0 212.045 212.045;" xml:space="preserve"> <path d="M167.871,0H44.84C34.82,0,26.022,8.243,26.022,18v182c0,3.266,0.909,5.988,2.374,8.091c1.752,2.514,4.573,3.955,7.598,3.954 c2.86,0,5.905-1.273,8.717-3.675l55.044-46.735c1.7-1.452,4.142-2.284,6.681-2.284c2.538,0,4.975,0.832,6.68,2.288l54.86,46.724 c2.822,2.409,5.657,3.683,8.512,3.683c4.828,0,9.534-3.724,9.534-12.045V18C186.022,8.243,177.891,0,167.871,0z"/> </svg>
            <span>{{ post.saved_by_user ? "Saved" : "Save" }}</span>
        </button>
        <div class="right-side">
            <button class="item item-three-dots" @click.prevent="switchContextMenu" :id="`btn-context-menu-${post.id}`" v-if="hasAtleastOneItem"></button>
            <ul class="context-menu" v-if="isContextMenuOpened">
                <li v-if="post.can.update_post">
                    <inertia-link :href="route('posts.edit', [post.sub_page_name, post.id])">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve"> <g> <path d="M496.063,62.299l-46.396-46.4c-21.2-21.199-55.69-21.198-76.888,0l-18.16,18.161l123.284,123.294l18.16-18.161 C517.311,117.944,517.314,83.55,496.063,62.299z"/> </g> <g> <path d="M22.012,376.747L0.251,494.268c-0.899,4.857,0.649,9.846,4.142,13.339c3.497,3.497,8.487,5.042,13.338,4.143 l117.512-21.763L22.012,376.747z"/> </g> <g> <polygon points="333.407,55.274 38.198,350.506 161.482,473.799 456.691,178.568 "/> </g> </svg>
                        Edit
                    </inertia-link>
                </li>
                <li v-if="post.can.delete_post">
                    <inertia-link method="delete" :href="route('posts.destroy', [post.sub_page_name, post.id])">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve"> <path d="M294.111,256.001L504.109,46.003c10.523-10.524,10.523-27.586,0-38.109c-10.524-10.524-27.587-10.524-38.11,0L256,217.892 L46.002,7.894c-10.524-10.524-27.586-10.524-38.109,0s-10.524,27.586,0,38.109l209.998,209.998L7.893,465.999 c-10.524,10.524-10.524,27.586,0,38.109c10.524,10.524,27.586,10.523,38.109,0L256,294.11l209.997,209.998 c10.524,10.524,27.587,10.523,38.11,0c10.523-10.524,10.523-27.586,0-38.109L294.111,256.001z"/> </svg>
                        Delete
                    </inertia-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import VotingButtons from "@/Components/VotingButtons"
import axios from "axios"

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
    data() {
        return {
            isContextMenuOpened: false
        }
    },
    computed: {
        hasAtleastOneItem() {
            for (const ability in this.post.can) {
                if(this.post.can[ability])
                    return true
            }
            return false
        }
    },
    methods: {
        switchContextMenu() {
            this.isContextMenuOpened = !this.isContextMenuOpened
        },
        savePost() {
            if(this.post.saved_by_user) {
                this.removeSavedPost()
                return
            }

            axios.post(route('posts.save.store', [this.post.sub_page_name, this.post.id]))
                .then(res => {
                    this.post.saved_by_user = true
                    this.emitter.emit("notify", { type: "info", payload: "Post saved!" });
                }).catch(err => {
                    this.emitter.emit("notify", { type: "error", payload: "Oops! An error occurred." });
                })
        },
        removeSavedPost() {
            axios.delete(route('posts.save.destroy', [this.post.sub_page_name, this.post.id]))
                .then(res => {
                    this.post.saved_by_user = false
                    this.emitter.emit("notify", { type: "info", payload: "The post has been removed from the saved!" });
                }).catch(err => {
                    this.emitter.emit("notify", { type: "error", payload: "Oops! An error occurred." });
                })
        },
        sharePost() {

        }
    },
    mounted() {
        document.addEventListener('click', (e) => {
            try {
                if(e.originalTarget.id !== `btn-context-menu-${this.post.id}`){
                    this.isContextMenuOpened = false
                }
            } catch (e) { }
        })
    }
}
</script>

