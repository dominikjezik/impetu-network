<template>
    <div class="auth-page">
        <form class="auth-box" @submit.prevent="submit">
            <div class="logo">
                <img src="/img/logo.png" alt="">
                <h1>Password reset</h1>
            </div>

            <div class="description">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" class="input" id="email" v-model="form.email" placeholder="john@example.com" required autofocus>
            </div>

            <div class="field field-btn">
                <button class="btn-primary">Email Password Reset Link</button>
            </div>
        </form>
        <div v-if="status" class="status">
            {{ status }}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            status: String
        },
        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    }
</script>
