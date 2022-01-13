<template>
  <v-container fluid className="pa-0">
    <LoginForm
      ref="loginForm"
      :login-handler="login"
      title="Connexion"
      reset-password-path="/reset-password"
      :error="error"
      class="pt-12"
    />
  </v-container>
</template>

<script>
import LoginForm from '../components/LoginForm'

export default {
  name: 'LoginMember',
  // layout: 'empty',
  components: {
    LoginForm
  },
  data() {
    return {
      error: ''
    }
  },
  methods: {
    parseJwt(token) {
      const base64Url = token.split('.')[1];
      const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      const jsonPayload = decodeURIComponent(atob(base64).split('').map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));

      return JSON.parse(jsonPayload);
    },
    async login() {
      try {
        this.error = ''
        await this.$auth.loginWith('local', {
          data: {
            email: this.$refs.loginForm.email,
            password: this.$refs.loginForm.password
          }
        })
        // this.$auth.setUser(this.parseJwt(this.$auth.strategy.token.get()))
        // if (!this.$auth.hasScope('ROLE_SUPER_ADMIN')) {
        //   this.$auth.logout()
        //   // throw new TypeError('Not authorized')
        //   this.error = 'Permission denied'
        // } else {
          // await this.$router.push('cfas')
        // }
      } catch (e) {
        if (e.code === 401) {
          this.error = 'Identifiants invalides'
        } else {
          console.error(e)
          this.error = e.message
        }
      }
    }
  }
}
</script>
