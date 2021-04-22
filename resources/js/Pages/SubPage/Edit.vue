<template>
    <master-layout>
        <div class="container subpage-content">
            <div class="fake-nav"></div>
            <div class="timeline">
                <div class="manage-subpage-page">
                    <h2 class="manage-title">Manage <span>{{ subpage.name }}</span> community</h2>

                    <form :action="`/r/${ subpage.name }/manage`" method="POST" class="box">
                        <h3>Basic information</h3>

                        <div class="field">
                            <label for="title">Title of Community</label>
                            <input id="title" type="text" class="input" placeholder="Title of Community" :value="subpage.title">
                        </div>

                        <div class="field">
                            <label for="description">Description of Community</label>
                            <textarea id="description" cols="30" rows="10" class="input" placeholder="Description of Community">{{ subpage.description }}</textarea>
                        </div>

                    </form>

                    <div class="box">
                        <h3>Roles of Community</h3>

                    </div>

                    <div class="box">
                        <h3>Appearance</h3>
                        photo & banner
                    </div>


                </div>


            </div>
            <section class="meta">
                <div class="meta-item basic-informations">
                    <div class="header">Informations</div>
                    <div class="content">
                        <p class="description">{{ subpage.description }}</p>
                        <div class="members">
                            <div class="item">
                                <span class="number">{{ subpage.members_count }}</span>
                                <span class="label">Members</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </master-layout>
</template>

<script>
import MasterLayout from "@/Layouts/MasterLayout";

export default {
    props: {
        subpage: Object
    },
    components: {
        MasterLayout,
    },
    mounted() {
        console.log(this.subpage)
    }
}
</script>
