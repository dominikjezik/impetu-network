<template>
    <div :id="`editor-${specialId}`" v-html="defaultText"></div>
</template>

<script>
import Quill from 'quill';

export default {
    props: {
        specialId: Number,
        defaultText: String,
    },
    data() {
        return {
            quill: null
        }
    },
    methods: {
        clear() {
            this.quill.setContents([
                { insert: '' }
            ])
        }
    },
    mounted() {
        this.quill = new Quill(`#editor-${this.specialId}`, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    ['clean']
                ]
            }
        });
    }
}
</script>
