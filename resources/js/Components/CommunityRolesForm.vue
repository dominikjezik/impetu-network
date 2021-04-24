<template>
    <ul class="roles">
        <li class="role" v-for="role in currentRoles">
            <div class="user">
                <img :src="`${role.user.profile_photo_url}`" alt="">
                <span class="name">{{ role.user.name }}</span>
                <span class="role-type" :class="role.type">{{ role.title }}</span>
            </div>
            <div class="options">
                <button @click="deleteRole(role.user)" v-if="canDelete(role)" class="btn-remove">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve"> <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88 c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242 C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879 s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/> </svg>
                </button>
            </div>
        </li>
    </ul>
    <button class="btn-primary-small btn-add-new-user"
        @click="displayAddUserForm"
        v-if="!addUserForm && currentUserRole !== 'moderator'"
    >Add user</button>

    <div class="add-new-user-form search" v-if="addUserForm">
        <h3>Assign a role to a new user</h3>
        <div v-if="selectedUser === null">
            <div class="search">
                <base-search-input
                    placeholder="Type name or email of user"
                    @data="updateResults"
                    :filters="searchFilters"
                />
            </div>

            <ul class="search-results">
                <li v-for="result in results" class="user" @click="selectUser(result)">
                    <img :src="`${result.profile_photo_url}`" alt="">
                    <div class="info">
                        <span class="name">{{ result.name }}</span>
                        <span class="name">{{ result.email }}</span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="selected" v-else>
            <div class="user">
                <img :src="`${selectedUser.profile_photo_url}`" alt="">
                <div class="info">
                    <span class="name">{{ selectedUser.name }}</span>
                    <span class="name">{{ selectedUser.email }}</span>
                </div>
            </div>
            <div class="options">
                <select class="input" ref="role">
                    <option value="admin" v-if="currentUserRole === 'owner'">Admin</option>
                    <option value="moderator" selected>Moderator</option>
                </select>
                <button class="btn-action btn-action-done" @click="submitNewUser">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"> <g> <g> <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0 c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7 C514.5,101.703,514.499,85.494,504.502,75.496z"/> </g> </g> </svg>
                </button>
                <button class="btn-action" @click="cancelSelectedUser">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve"> <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88 c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242 C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879 s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/> </svg>
                </button>
            </div>
        </div>
    </div>

</template>

<script>
import axios from "axios"
import BaseSearchInput from "@/Components/BaseSearchInput";

export default {
    components: {BaseSearchInput},
    props: {
        roles: Array,
        subpage: Object
    },
    data() {
        return {
            currentRoles: this.roles,
            addUserForm: false,
            results: [],
            selectedUser: null,
            searchFilters: {
                entity: "users",
                usersFrom: "test"
            }
        }
    },
    computed: {
        currentUserRole() {
            const role = this.subpage.roles.find(role => role.user.email === this.$page.props.user.email)
            return role.type
        }
    },
    methods: {
        displayAddUserForm() {
            this.addUserForm = true
        },
        updateResults(results) {
            const filtered = []
            results.forEach(i => {
                if(this.currentRoles.filter(roleUser => roleUser.user.email === i.email ).length === 0) {
                    filtered.push(i)
                }
            })
            this.results = filtered
        },
        selectUser(user) {
            this.selectedUser = user
            this.results = []
        },
        cancelSelectedUser() {
            this.selectedUser = null
        },
        submitNewUser() {
            axios.post(route('roles.store', this.subpage.name), {
                user: this.selectedUser.id,
                role: this.$refs.role.value
            }).then(res => {
                this.currentRoles.push({
                    type: this.$refs.role.value,
                    title: this.capitalizeFirstLetter(this.$refs.role.value),
                    user: this.selectedUser
                })
                this.cancelSelectedUser()
            }).catch(err => {
                console.log(err)
            })
        },
        canDelete(role) {
            if(role.type === this.currentUserRole) {
                return false
            }
            if(this.currentUserRole === "moderator") {
                return false
            }
            if(this.currentUserRole === "admin" && role.type === "owner") {
                return false
            }
            return true
        },
        deleteRole(user) {
            axios.post(route('roles.destroySomeoneElseRole', this.subpage.name), {
                _method: "DELETE",
                user: user.id
            }).then(res => {
                this.currentRoles = this.currentRoles.filter(i => i.user.id !== user.id)
            }).catch(err => {
                console.log(err)
            })
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    }
}
</script>
