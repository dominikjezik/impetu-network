<template>
    <input
        type="text"
        class="input search-input"
        :placeholder="placeholder"
        v-model="query"
        @focus="$emit('focus')"
    >
    <svg class="search-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="612.01px" height="612.01px" viewBox="0 0 612.01 612.01" xml:space="preserve">   <g><g><g><path class="st0" d="M606.209,578.714L448.198,423.228C489.576,378.272,515,318.817,515,253.393C514.98,113.439,399.704,0,257.493,0 C115.282,0,0.006,113.439,0.006,253.393s115.276,253.393,257.487,253.393c61.445,0,117.801-21.253,162.068-56.586 l158.624,156.099c7.729,7.614,20.277,7.614,28.006,0C613.938,598.686,613.938,586.328,606.209,578.714z M257.493,467.8 c-120.326,0-217.869-95.993-217.869-214.407S137.167,38.986,257.493,38.986c120.327,0,217.869,95.993,217.869,214.407 S377.82,467.8,257.493,467.8z"/></g></g></g></svg>
    <button class="btn-clear" @click="clearQuery" v-if="query.length > 0">
        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve">
            <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88
                c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242
                C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879
                s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/>
        </svg>
    </button>
</template>

<script>
import axios from "axios";

export default {
    emits: ['loading', 'data', 'focus', 'query'],
    props: {
        placeholder: {
            type: String,
            default: "Search Impetu"
        },
        searchableEntity: {
            type: String,
            default: "communities"
        }
    },
    data() {
        return {
            query: "",
            timeout: null
        }
    },
    watch: {
        query(value) {
            this.$emit('query', value)
            this.debounce( () => {
                this.fetchResults()
            }, 500)()
        }
    },
    methods: {
        clearQuery() {
            this.query = ""
            this.$emit('query', "")
            this.$emit('data', [])
        },
        fetchResults() {
            if(this.query === "") {
                this.$emit('data', [])
                return
            }

            this.$emit('loading', true)
            axios.get('/api/search', {
                params: {
                    q: this.query,
                    entity: this.searchableEntity
                }
            }).then(({data}) => {
                this.$emit('loading', false)
                this.$emit('data', data.data)

            }).catch(err => {
                this.$emit('loading', false)
                console.log(err)
            })
        },
        debounce(callback, wait) {
            return (...args) => {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(function () { callback.apply(this, args); }, wait);
            };
        }
    }
}
</script>
