<template>
    <div>
        <transition-group name="list" tag="ul" class="pop-up-notifications">
            <li v-for="(notification, i) in notifications" :key="notification" class="pop-up-notification">
                <div class="left">
                    <svg v-if="notification.type === 'info'" viewBox="0 0 68 68" class="icon icon-info" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M34 63C50.0163 63 63 50.0163 63 34C63 17.9837 50.0163 5 34 5C17.9837 5 5 17.9837 5 34C5 50.0163 17.9837 63 34 63ZM34 68C52.7777 68 68 52.7777 68 34C68 15.2223 52.7777 0 34 0C15.2223 0 0 15.2223 0 34C0 52.7777 15.2223 68 34 68Z" fill="black"/> <path d="M34.33 50.715C33.52 50.715 32.845 50.49 32.305 50.04C31.795 49.56 31.54 48.885 31.54 48.015V30.645C31.54 29.775 31.795 29.115 32.305 28.665C32.845 28.215 33.52 27.99 34.33 27.99C35.14 27.99 35.815 28.215 36.355 28.665C36.895 29.115 37.165 29.775 37.165 30.645V48.015C37.165 48.885 36.895 49.56 36.355 50.04C35.815 50.49 35.14 50.715 34.33 50.715ZM34.33 23.985C33.31 23.985 32.5 23.715 31.9 23.175C31.3 22.605 31 21.87 31 20.97C31 20.07 31.3 19.35 31.9 18.81C32.5 18.27 33.31 18 34.33 18C35.32 18 36.115 18.27 36.715 18.81C37.345 19.35 37.66 20.07 37.66 20.97C37.66 21.87 37.36 22.605 36.76 23.175C36.16 23.715 35.35 23.985 34.33 23.985Z" fill="black"/> </svg>
                    <svg v-else-if="notification.type === 'error'" class="icon icon-error" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"> <g> <path d="M256,0C114.508,0,0,114.497,0,256c0,141.493,114.497,256,256,256c141.492,0,256-114.497,256-256 C512,114.507,397.503,0,256,0z M256,472c-119.384,0-216-96.607-216-216c0-119.385,96.607-216,216-216 c119.384,0,216,96.607,216,216C472,375.385,375.393,472,256,472z"/> </g> <g> <path d="M343.586,315.302L284.284,256l59.302-59.302c7.81-7.81,7.811-20.473,0.001-28.284c-7.812-7.811-20.475-7.81-28.284,0 L256,227.716l-59.303-59.302c-7.809-7.811-20.474-7.811-28.284,0c-7.81,7.811-7.81,20.474,0.001,28.284L227.716,256 l-59.302,59.302c-7.811,7.811-7.812,20.474-0.001,28.284c7.813,7.812,20.476,7.809,28.284,0L256,284.284l59.303,59.302 c7.808,7.81,20.473,7.811,28.284,0C351.398,335.775,351.397,323.112,343.586,315.302z"/> </g> </svg>
                    <svg v-else class="icon icon-success" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"> <g> <g> <path d="M497.36,69.995c-7.532-7.545-19.753-7.558-27.285-0.032L238.582,300.845l-83.522-90.713 c-7.217-7.834-19.419-8.342-27.266-1.126c-7.841,7.217-8.343,19.425-1.126,27.266l97.126,105.481 c3.557,3.866,8.535,6.111,13.784,6.22c0.141,0.006,0.277,0.006,0.412,0.006c5.101,0,10.008-2.026,13.623-5.628L497.322,97.286 C504.873,89.761,504.886,77.54,497.36,69.995z"/> </g> </g> <g> <g> <path d="M492.703,236.703c-10.658,0-19.296,8.638-19.296,19.297c0,119.883-97.524,217.407-217.407,217.407 c-119.876,0-217.407-97.524-217.407-217.407c0-119.876,97.531-217.407,217.407-217.407c10.658,0,19.297-8.638,19.297-19.296 C275.297,8.638,266.658,0,256,0C114.84,0,0,114.84,0,256c0,141.154,114.84,256,256,256c141.154,0,256-114.846,256-256 C512,245.342,503.362,236.703,492.703,236.703z"/> </g> </g> </svg>
                    {{ notification.payload }}
                </div>
                <button class="btn-close" @click="closeNotification(i)">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve"> <path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88 c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242 C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879 s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z"/> </svg>
                </button>
            </li>
        </transition-group>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notifications: []
        }
    },
    methods: {
        closeNotification(position) {
            this.notifications.splice(position, 1)
        },
        displayNotificationTemporarily(arg) {
            this.notifications.push(arg)

            setTimeout(() => {
                let index = this.notifications.indexOf(arg);
                if (index !== -1) {
                    this.notifications.splice(index, 1);
                }
            }, 4000)

        }
    },
    mounted() {
        if(this.$page.props.flash.message.payload !== null) {
            this.displayNotificationTemporarily({
                type: this.$page.props.flash.message.type,
                payload: this.$page.props.flash.message.payload
            })
        }

        this.emitter.on("notify", arg => {
            this.displayNotificationTemporarily(arg)
        });
    }
}
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all .5s ease-in-out;
}
.list-enter-from,
.list-leave-to {
    transform: translateX(120%);
}

</style>
