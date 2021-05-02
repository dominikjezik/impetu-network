<template>
    <div class="auth-page">
        <form class="auth-box" @submit.prevent="submit">
            <div class="logo">
                <img src="/img/logo.png" alt="">
                <h1>Password reset</h1>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" class="input" id="email" v-model="form.email" placeholder="john@example.com" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" class="input" id="password" v-model="form.password" placeholder="********" required>
                <div class="error-msg" v-if="$page.props.errors.password">{{ $page.props.errors.password }}</div>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="input" id="password_confirmation" v-model="form.password_confirmation" placeholder="********" required>
                <div class="error-msg" v-if="$page.props.errors.password_confirmation">{{ $page.props.errors.password_confirmation }}</div>
            </div>

            <div class="field field-btn">
                <button class="btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            email: String,
            token: String,
        },
        data() {
            return {
                form: this.$inertia.form({
                    token: this.token,
                    email: this.email,
                    password: '',
                    password_confirmation: '',
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('password.update'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
