<template>
    <div :id="`editor-${specialId}`"></div>
</template>

<script>
import Quill from 'quill';

export default {
    props: {
        specialId: String
    },
    data() {
        return {
            quill: null
        }
    },
    methods: {
        clear() {
            this.quill.setText('')
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
