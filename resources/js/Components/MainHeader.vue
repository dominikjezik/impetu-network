<template>
    <header class="main-header">
        <div class="logo">
            <inertia-link href="/">
                <img src="/img/logo.png" alt="">
                <span>Impetu network</span>
            </inertia-link>
        </div>

        <primary-search-bar />
        <div class="options" v-if="$page.props.user !== null">
            <button class="btn-notification" @click="this.isNotificationWindowOpened = !this.isNotificationWindowOpened">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"> <g> <path d="M256,0c-32.563,0-59.054,26.492-59.054,59.054s26.492,59.054,59.054,59.054s59.054-26.492,59.054-59.054S288.563,0,256,0z M256,82.676c-13.246,0-23.622-10.376-23.622-23.622c0-13.027,10.594-23.622,23.622-23.622s23.622,10.594,23.622,23.622 C279.622,72.3,269.246,82.676,256,82.676z"/> </g> <g> <path d="M256,84.448c-87.595,0-158.856,72.058-158.856,160.628V348.42h35.433V245.075c0-69.034,55.369-125.195,123.423-125.195 s123.423,56.161,123.423,125.195v100.983h35.432V245.075C414.856,156.505,343.595,84.448,256,84.448z"/> </g> <g> <path d="M447.926,330.704H64.074c-9.785,0-17.716,7.931-17.716,17.716v81.495c0,9.785,7.931,17.716,17.716,17.716h383.852 c9.785,0,17.716-7.931,17.716-17.716V348.42C465.642,338.635,457.711,330.704,447.926,330.704z M430.21,412.198H81.79v-46.062 h348.42V412.198z"/> </g> <g> <path d="M302.653,429.915c0,26.155-20.492,46.653-46.653,46.653s-46.653-20.492-46.653-46.653h-35.432 C173.915,475.18,210.735,512,256,512s82.085-36.82,82.085-82.085H302.653z"/> </g> </svg>
            </button>
            <notifications-window v-if="isNotificationWindowOpened" />

            <button class="user" @click="this.isContextMenuOpened = !this.isContextMenuOpened" id="btn-main-user">
                <img :src="$page.props.user.profile_photo_url" id="btn-main-user-photo" />
                {{ $page.props.user.name }}
            </button>
            <ul class="context-menu" v-if="isContextMenuOpened">
                <li>
                    <inertia-link href="#settings">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"> <g> <path d="M255.997,149.109c-58.775,0-106.592,47.817-106.592,106.592c0,58.775,47.817,106.592,106.592,106.592 c58.769,0,106.592-47.817,106.592-106.592C362.589,196.925,314.766,149.109,255.997,149.109z M255.997,326.363 c-38.966,0-70.662-31.696-70.662-70.662c0-38.966,31.696-70.662,70.662-70.662s70.662,31.696,70.662,70.662 C326.659,294.667,294.963,326.363,255.997,326.363z"/> <path d="M474.361,179.871l-25.564-4.258l14.552-20.702c13.222-17.636,11.228-42.954-4.216-57.422l-45.511-45.511 c-15.552-15.552-40.379-17.24-57.392-4.234l-20.264,14.713l-4.078-24.169C328.857,16.103,310.539,0,288.334,0H223.66 c-21.761,0-40.475,16.156-43.469,37.169l-4.288,25.738l-20.702-14.546c-17.642-13.234-42.966-11.24-57.422,4.21L52.269,98.082 c-15.546,15.54-17.252,40.367-4.3,57.296l14.989,20.923l-24.366,4.114c-22.193,3.024-38.295,21.336-38.295,43.547v64.674 c0,22.121,15.803,40.427,37.163,43.463l25.738,4.294l-14.546,20.702c-13.228,17.636-11.234,42.954,4.21,57.416l45.517,45.517 c8.168,8.174,19.857,13.049,31.265,13.049c9.408,0,18.516-3.108,26.031-8.749l20.923-14.989l4.114,24.366 C183.73,495.897,202.048,512,224.259,512h64.674c22.115,0,40.427-15.809,43.457-37.157l4.294-25.744l20.51,14.408 c7.952,5.958,17.019,8.982,26.941,8.982c11.749,0,22.857-4.641,31.271-13.055l45.511-45.511 c15.552-15.54,17.252-40.367,4.12-57.554l-15.432-20.761l23.803-4.012c22.193-3.03,38.295-21.348,38.295-43.553V223.37 C511.704,201.692,495.673,183.033,474.361,179.871z M475.768,288.026c0,4.174-2.91,7.366-7.785,8.042l-53.29,8.982 c-6.162,1.036-11.342,5.21-13.677,11.013s-1.479,12.396,2.246,17.42l33.079,44.505c2.24,2.922,1.85,7.845-0.826,10.521 l-45.511,45.511c-1.725,1.725-3.587,2.527-5.863,2.527c-2.096,0-3.707-0.533-5.839-2.12l-44.313-31.139 c-5.054-3.563-11.581-4.246-17.27-1.874c-5.689,2.383-9.767,7.527-10.779,13.617l-9.048,54.308 c-0.557,3.898-3.904,6.731-7.964,6.731h-64.674c-4.162,0-7.36-2.904-8.03-7.779l-8.982-53.296 c-1.03-6.108-5.138-11.252-10.869-13.617c-2.204-0.916-4.533-1.359-6.845-1.359c-3.695,0-7.366,1.138-10.462,3.353 l-44.637,31.977c-1.587,1.192-3.198,1.797-4.785,1.797c-1.844,0-4.473-1.126-5.863-2.521l-45.93-45.918 c-2.617-2.449-2.803-6.988-0.413-10.108c0.15-0.192,0.293-0.389,0.431-0.587l31.139-44.313c3.551-5.054,4.258-11.575,1.874-17.27 c-2.383-5.689-7.527-9.767-13.617-10.779l-54.308-9.048c-3.898-0.557-6.731-3.904-6.731-7.964v-64.674 c0-4.168,2.898-7.36,7.773-8.036l53.296-8.982c6.114-1.03,11.258-5.138,13.623-10.869c2.365-5.731,1.611-12.276-2-17.312 L76.845,134c-2.234-2.928-1.85-7.845,0.826-10.521l45.912-45.924c2.455-2.623,6.988-2.797,10.114-0.407 c0.192,0.144,0.383,0.287,0.581,0.425l44.313,31.139c5.048,3.551,11.569,4.264,17.27,1.874 c5.695-2.383,9.767-7.527,10.779-13.617l9.048-54.308c0.557-3.898,3.904-6.731,7.964-6.731h64.674 c4.174,0,7.366,2.904,8.048,7.785l8.982,53.296c1.03,6.132,5.162,11.294,10.917,13.647c5.749,2.347,12.312,1.557,17.348-2.096 l44.068-32.008c2.916-2.234,7.839-1.856,10.521,0.826l45.924,45.912c2.611,2.449,2.797,6.994,0.407,10.114 c-0.138,0.192-0.287,0.383-0.419,0.581l-31.139,44.313c-3.551,5.054-4.252,11.575-1.874,17.27 c2.383,5.695,7.527,9.767,13.617,10.779l53.895,8.982c0.138,0.024,0.275,0.048,0.413,0.066c3.898,0.557,6.731,3.904,6.731,7.964 V288.026z"/> </g> </svg>
                        Settings
                    </inertia-link>
                    <inertia-link method="post" :href="route('logout')">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"> <g> <path d="M255.15,468.625H63.787c-11.737,0-21.262-9.526-21.262-21.262V64.638c0-11.737,9.526-21.262,21.262-21.262H255.15 c11.758,0,21.262-9.504,21.262-21.262S266.908,0.85,255.15,0.85H63.787C28.619,0.85,0,29.47,0,64.638v382.724 c0,35.168,28.619,63.787,63.787,63.787H255.15c11.758,0,21.262-9.504,21.262-21.262 C276.412,478.129,266.908,468.625,255.15,468.625z"/> </g> <g> <path d="M505.664,240.861L376.388,113.286c-8.335-8.25-21.815-8.143-30.065,0.213s-8.165,21.815,0.213,30.065l92.385,91.173 H191.362c-11.758,0-21.262,9.504-21.262,21.262c0,11.758,9.504,21.263,21.262,21.263h247.559l-92.385,91.173 c-8.377,8.25-8.441,21.709-0.213,30.065c4.167,4.21,9.653,6.336,15.139,6.336c5.401,0,10.801-2.041,14.926-6.124l129.276-127.575 c4.04-3.997,6.336-9.441,6.336-15.139C512,250.302,509.725,244.88,505.664,240.861z"/> </g> </svg>
                        Logout
                    </inertia-link>
                </li>
            </ul>
        </div>
    </header>
</template>

<script>
import PrimarySearchBar from "@/Components/PrimarySearchBar";
import NotificationsWindow from "@/Components/NotificationsWindow";

export default {
    components: {
        PrimarySearchBar,
        NotificationsWindow
    },
    data() {
        return {
            isContextMenuOpened: false,
            isNotificationWindowOpened: false
        }
    },
    mounted() {
        document.addEventListener('click', (e) => {
            try {
                if(e.originalTarget.id !== 'btn-main-user' && e.originalTarget.id !== 'btn-main-user-photo'){
                    this.isContextMenuOpened = false
                }
            } catch (e) { }
        })
    }
}
</script>
