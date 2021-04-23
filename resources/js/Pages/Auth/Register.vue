<template>
    <div class="auth-page">
        <form class="auth-box" @submit.prevent="submit">
            <div class="logo">
                <img src="/img/logo.png" alt="">
                <h1>Create an account</h1>
            </div>

            <div class="field">
                <label for="name">Name</label>
                <input type="text" class="input" id="name" v-model="form.name" placeholder="John Doe" required autofocus>
                <div class="error-msg" v-if="$page.props.errors.name">{{ $page.props.errors.name }}</div>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" class="input" id="email" v-model="form.email" placeholder="john@example.com" required>
                <div class="error-msg" v-if="$page.props.errors.email">{{ $page.props.errors.email }}</div>
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

            <div class="field-btn-register">
                <inertia-link :href="route('login')">
                    Already registered?
                </inertia-link>

                <button class="btn-primary" :disabled="form.processing">Register</button>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
