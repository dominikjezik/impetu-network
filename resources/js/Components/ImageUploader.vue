<template>
    <div>
        <div class="control">
            <label>{{ title }}</label>
            <button v-show="image" class="btn-outline-small" @click.prevent="$refs.imageCroppie.cropAndSendToForm()">Crop photo</button>
            <button v-show="image" class="btn-danger-small" @click.prevent="image = null">Remove</button>
        </div>

        <div class="content" v-show="!image">
            <div class="actual-image"
                 @dragenter="onDragEnter"
                 @dragleave="onDragLeave"
                 @dragover.prevent
                 @drop="onDrop"
                 :class="{dragging: isDragging}"
                 @click="$refs.photo.click();"
            >
                <img ref="profilePhoto" :src="defaultImageUrl" alt="">
                <div class="hover">
                    <span>Drag and drop your <br> new photo here</span>
                </div>
            </div>
            <div class="options">
                <div class="file-input">
                    <label for="file" class="btn-outline-small">Select a new photo</label>
                    <input type="file" id="file" ref="photo" @change="onInputChange">
                </div>
<!--                <button class="btn-danger">Remove image</button>-->
            </div>
        </div>

        <div class="croppie">
            <image-croppie ref="imageCroppie" v-if="image" :img-src="image" @croppedUploadedImage="croppedUploadedImage" />
        </div>
    </div>
</template>

<script>
import ImageCroppie from "./ImageCroppie";

export default {
    components: {
        ImageCroppie
    },
    props: {
        title: String,
        defaultImageUrl: String
    },
    data() {
        return {
            isDragging: false,
            dragCount: 0,
            file: null,
            image: null
        }
    },
    methods: {
        onInputChange(e) {
            const files = e.target.files;

            Array.from(files).forEach(file => this.addImage(file));
        },
        onDragEnter(e) {
            e.preventDefault();
            this.dragCount++;
            this.isDragging = true;
        },
        onDragLeave(e) {
            e.preventDefault();
            this.dragCount--;

            if(this.dragCount <= 0)
                this.isDragging = false;
        },
        onDrop(e) {
            e.preventDefault();
            e.stopPropagation();

            this.isDragging = false;

            const files = e.dataTransfer.files;

            Array.from(files).forEach(file => this.addImage(file));
        },
        addImage(file) {

            if (!file.type.match('image.*')) {
                console.log(`${file.name} is not image`);
                return
            }

            this.file = file;

            const reader = new FileReader()
            reader.onload = (e) => this.image = e.target.result;
            reader.readAsDataURL(file);

        },
        croppedUploadedImage(image) {
            this.$emit('croppedUploadedImage', image);
            this.$refs.profilePhoto.src = image;
            this.image = null;
        }
    }
}
</script>

<style lang="scss" scoped>

    .control {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        label {
            color: #676767;
            font-size: 0.875rem;
            display: block;
        }
        button {
            margin-left: 1rem;
        }

    }

    .file-input {
        margin-right: 1rem;
        input {
            display: none;
        }
        label {
            display: block;
        }
    }

    .options {
        margin: 1rem 0;
        display: flex;
        align-items: center;
    }

    .actual-image {
        flex-shrink: 0;
        overflow: hidden;
        width: 12rem;
        height: 12rem;
        position: relative;
        border-radius: 50%;

        img,
        .hover {
            position: absolute;
        }

        img {
            width: 100%;
        }

        &.dragging .hover,
        &:hover .hover {
            opacity: 1;
        }

        &.dragging .hover {
            background: rgba(244, 118, 39, 0.6);
        }

        .hover {
            opacity: 0;
            transition-duration: .3s;
            transition-property: all;
            transition-timing-function: ease-out;
            cursor: pointer;
            text-align: center;
            color: #fff;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            span {
                user-select: none;
                cursor: inherit;
                position: absolute;
                top: 50%;
                left: 50%;
                width: 100%;
                transform: translate(-50%, -50%);
            }
        }
    }
</style>
