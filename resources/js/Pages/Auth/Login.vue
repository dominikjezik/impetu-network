<template>
    <div class="auth-page">
        <form class="auth-box" @submit.prevent="submit">
            <div class="logo">
                <img src="/img/logo.png" alt="">
                <h1>Impetu Network</h1>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" class="input" id="email" v-model="form.email" placeholder="john@example.com" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" class="input" id="password" v-model="form.password" placeholder="********" required>
            </div>

            <div class="row">
                <div class="remember-me">
                    <label>
                        <input type="checkbox" name="remember" v-model:checked="form.remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </inertia-link>
            </div>

            <div class="error-msg" v-if="$page.props.errors.email">{{ $page.props.errors.email }}</div>

            <div class="field field-btn">
                <button class="btn-primary">Log in</button>
                <span class="create-account">
                    or
                    <inertia-link :href="route('register')">Create an account</inertia-link>
                </span>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    }
</script>
