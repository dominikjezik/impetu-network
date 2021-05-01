<template>
    <div class="appearance">
        <image-uploader
            title="Community photo"
            :default-image-url="subpage.photo_url === null ? `/img/logo.png` : subpage.photo_url"
            @croppedUploadedImage="setNewCroppedUploadedImage"
        />
        <button class="btn-danger-small" @click="removePhoto" v-if="subpage.photo_url !== null">Remove a photo</button>

        <div>
            <div class="title">Community banner</div>
            <div class="file-input">
                <label for="banner" class="btn-outline-small">{{ bannerBtnTitle }}</label>
                <input type="file" id="banner" class="hide-input" @input="form.banner = $event.target.files[0]">
            </div>
        </div>
        <button class="btn-danger-small btn-remove-banner" @click="removeBanner" v-if="subpage.banner_url !== null">Remove a banner</button>
        <br>

        <button @click="submit" class="btn-primary-small submit">Update</button>
    </div>
</template>

<script>
import ImageUploader from "@/Components/ImageUploader";

export default {
    components: {
        ImageUploader
    },
    props: {
        subpage: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                photo: null,
                banner: null
            }),
        }
    },
    computed: {
        bannerBtnTitle() {
            return this.form.banner === null ? "Select a new banner" : "1 file selected"
        }
    },
    methods: {
        submit() {
            this.form.post(route('subpages.media.store', this.subpage.name))
        },
        setNewCroppedUploadedImage(image) {
            this.form.photo = image;
        },
        removePhoto() {
            this.$inertia.post(route('subpages.media.destroyPhoto', this.subpage.name), {
                _method: "DELETE"
            })
        },
        removeBanner() {
            this.$inertia.post(route('subpages.media.destroyBanner', this.subpage.name), {
                _method: "DELETE"
            })
        }
    },
    mounted() {
        console.log(this.subpage)
    }
}
</script>

