<template>
    <div class="search">
        <base-search-input
            @loading="updateLoading"
            @data="updateData"
            @focus="openResults"
            @query="updateQuery"
        />
        <ul class="results" v-if="displayResults">
            <li class="result" v-for="item in items">
                <inertia-link :href="`/r/${item.name}`">
                    <img src="" alt="">
                    <div class="info">
                        <h5 class="title">{{ item.title }}</h5>
                        <span class="members">{{ item.members_count }} member{{ item.members_count !== 1 ? 's' : '' }}</span>
                    </div>
                </inertia-link>
            </li>
            <li class="no-results" v-if="items.length === 0 && query !=='' && !loading">
                Sorry, there were no results for “{{ query }}”
            </li>
            <li class="loading" v-if="loading">
                <span></span>
                <span></span>
                <span></span>
            </li>
        </ul>
    </div>
</template>

<script>
import BaseSearchInput from "@/Components/BaseSearchInput";
import axios from 'axios'

export default {
    components: {
        BaseSearchInput
    },
    data() {
        return {
            displayResults: false,
            loading: false,
            items: [],
            query: ""
        }
    },
    methods: {
        openResults() {
            this.displayResults = true
        },
        updateData(items) {
            this.items = items
        },
        updateLoading(value) {
            this.loading = value
        },
        updateQuery(query) {
            this.query = query
        }
    }
}


</script>
