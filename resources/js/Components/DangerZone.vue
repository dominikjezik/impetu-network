<template>
    <div class="box-danger-zone">
        <h2>Danger zone</h2>
<!--        <div class="box" v-if="subpage.can.change_owner_of_sub_page">-->
<!--            <h3>Change community owner</h3>-->
<!--            <div class="field">-->
<!--                <label for="new-owner">Write the email of the new community owner (He must be already Admin). You will receive Admin role.</label>-->
<!--                <input type="text" class="input" placeholder="..." id="new-owner">-->
<!--                <button class="btn-danger-small" disabled>Give up ownership and change owner</button>-->
<!--            </div>-->
<!--        </div>-->
        <div class="box" v-if="currentUserRole !== 'owner'">
            <h3>Give up the {{ currentUserRole }} role</h3>
            <div class="field">
                <label for="role">Write the type of role you are giving up.</label>
                <input type="text" class="input" :placeholder="currentUserRole" id="role" v-model="typedRoleForControl">
                <button class="btn-danger-small" @click="giveupRole" :disabled="disableGiveupRoleButton">Give up the role</button>
            </div>
        </div>
        <div class="box" v-if="subpage.can.delete_sub_page">
            <h3>Delete community</h3>
            <div class="field">
                <label for="name">Write the name of the community you want to delete.</label>
                <input type="text" class="input" :placeholder="subpage.name" id="name" v-model="typedNameForControl">
                <button class="btn-danger-small" @click="deleteCommunity" :disabled="disableDeleteSubpageButton">Delete Community</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        subpage: Object
    },
    data() {
        return {
            disableDeleteSubpageButton: true,
            disableGiveupRoleButton: true,
            typedNameForControl: "",
            typedRoleForControl: ""
        }
    },
    computed: {
        currentUserRole() {
            const role = this.subpage.roles.find(role => role.user.email === this.$page.props.user.email)
            return role.type
        }
    },
    watch: {
        typedNameForControl(value) {
            this.disableDeleteSubpageButton = value !== this.subpage.name
        },
        typedRoleForControl(value) {
            this.disableGiveupRoleButton = value !== this.currentUserRole
        }
    },
    methods: {
        deleteCommunity() {
            this.$inertia.delete(route('subpages.destroy', this.subpage.name))
        },
        giveupRole() {
            this.$inertia.delete(route('roles.destroy', this.subpage.name))
        }
    }
}
</script>
