<template>
    <master-layout>
        <div class="container subpage-content">
            <div class="fake-nav"></div>
            <div class="timeline">
                <div class="manage-subpage-page">
                    <h2 class="manage-title">Manage <span>{{ subpage.name }}</span> community</h2>

                    <div class="box">
                        <h3>Basic information</h3>

                        <div class="field">
                            <label for="title">Title of Community</label>
                            <input
                                id="title" type="text"
                                class="input" placeholder="Title of Community"
                                v-model="updateForm.title"
                                :disabled="!subpage.can.update_basic_information"
                            >
                        </div>

                        <div class="field">
                            <label for="description">Description of Community</label>
                            <textarea id="description"
                                cols="30" rows="10"
                                class="input" placeholder="Description of Community"
                                v-model="updateForm.description"
                                :disabled="!subpage.can.update_basic_information"
                            ></textarea>
                        </div>

                        <button class="btn-primary-small"
                            @click="updateInfo"
                            v-if="subpage.can.update_basic_information"
                        >Update</button>

                    </div>

                    <div class="box">
                        <h3>Roles of Community</h3>

                        <community-roles-form :roles="subpage.roles" :subpage="subpage" />
                    </div>

                    <div class="box">
                        <h3>Appearance</h3>
                        photo & banner
                    </div>


                </div>

                <danger-zone :subpage="subpage" />

            </div>
            <section class="meta"></section>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";
import DangerZone from "@/Components/DangerZone";
import CommunityRolesForm from "@/Components/CommunityRolesForm";

export default {
    props: {
        subpage: Object
    },
    components: {
        MasterLayout,
        DangerZone,
        CommunityRolesForm
    },
    data() {
        return {
            updateForm: {
                title: this.subpage.title,
                description: this.subpage.description
            }
        }
    },
    methods: {
        updateInfo() {
            this.$inertia.patch(route('subpages.udpate', this.subpage.name), this.updateForm)
        }
    }
}
</script>
