<template>
    <div>
        <div ref="croppie" class="croppie"></div>
<!--        <button @click.prevent="cropAndSendToForm" class="btn">Crop Photo</button>-->
        <img v-if="resultImgSrc" :src="resultImgSrc" alt="">
    </div>
</template>

<script>
    import Croppie from 'croppie';
    import 'croppie/croppie.css';

    export default {
        data() {
            return {
                croppie: null,
                resultImgSrc: null
            }
        },
        props: [
            'imgSrc',
        ],
        mounted() {
            this.croppie = new Croppie(this.$refs.croppie, {
                enableExif: true,
                viewport: {
                    width: 250,
                    height: 250,
                    type: 'square'
                },
                boundary: { width: 250, height: 250 },
                showZoomer: true,
                enableOrientation: true
            });

            this.croppie.bind({
                url: this.imgSrc,
            });
        },
        methods: {
            cropAndSendToForm() {
                this.croppie.result({
                    type: 'canvas',
                    size: 'viewport'
                }).then(response => {
                    this.$emit('croppedUploadedImage', response);
                });
            }
        }
    }
</script>

<style lang="scss">
    .croppie {
        width: 250px;

        .cr-viewport.cr-vp-square {
            border-radius: 50%;
        }
    }
</style>
