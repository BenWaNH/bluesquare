<template>
  <!--  <v-row-->
  <!--    align="center"-->
  <!--    justify="center"-->
  <!--    class="mt-12"-->
  <!--  >-->
  <!--    <v-col-->
  <!--      cols="12"-->
  <!--      sm="8"-->
  <!--      md="5"-->
  <!--    >-->
  <div class="mx-auto" style="max-width: 600px">
    <v-card class="elevation-12 mx-auto mt-12" max-width="600">
      <v-toolbar
        color="#55565A"
        dark
        flat
      >
        <v-toolbar-title>{{ title }}</v-toolbar-title>
        <v-spacer />
      </v-toolbar>
      <v-card-text>
        <v-form @keyup.native.enter="loginHandler">
          <v-text-field
            v-model="email"
            label="Email"
            name="login"
            prepend-icon="mdi-account"
            type="text"
          />

          <v-text-field
            id="password"
            v-model="password"
            :append-icon="showPwd ? 'mdi-eye' : 'mdi-eye-off'"
            label="Mot de passe"
            name="password"
            prepend-icon="mdi-lock"
            :type="showPwd ? 'text' : 'password'"
            @click:append="showPwd = !showPwd"
          />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <!--          <v-layout>-->
        <!--          <v-row>-->
        <v-flex>
          <nuxt-link
            v-if="resetPasswordPath"
            :to="resetPasswordPath"
            class="caption"
          >
            Mot de passe oublié ?
          </nuxt-link>
        </v-flex>
        <v-spacer />
        <!--            <v-col>-->
        <v-btn
          :loading="loading"
          :disabled="loading"
          color="deep-orange"
          class="white--text"
          @click="login"
        >
          Se connecter
        </v-btn>
        <!--            </v-col>-->
        <!--          </v-row>-->
        <!--          <v-row>-->
        <!--            <v-col>-->

        <!--            </v-col>-->
        <!--          </v-row>-->
        <!--          </v-layout>-->
      </v-card-actions>
    </v-card>

    <v-alert
      v-if="error"
      width="100%"
      border="top"
      color="red lighten-2"
      dark
    >
      {{ error }}
    </v-alert>
  </div>
  <!--    </v-col>-->
  <!--  </v-row>-->
</template>

<script>
export default {
  name: 'LoginForm',
  props: {
    title: {
      type: String,
      default: 'Connexion'
    },
    loginHandler: {
      type: Function,
      required: true
    },
    registerPath: {
      type: String,
      default: ''
    },
    resetPasswordPath: {
      type: String,
      default: ''
    },
    error: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      email: '',
      password: '',
      loading: false,
      showPwd: false
    }
  },
  methods: {
    login () {
      this.loading = true
      this.loginHandler().then(() => {
        this.loading = false
      })
    }
  }
}
</script>
