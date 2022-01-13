<template>
  <v-form>
    <v-container fluid>
      <v-row>
        <v-col cols="12" sm="2" md="2">
          <v-select
            v-model.number="item.civility"
            :error-messages="civilityErrors"
            label="Civilité"
            required
            class="required"
            :items="[{value: 0, text:'Monsieur'}, {value: 1, text:'Madame'}]"
            @input="$v.item.civility.$touch()"
            @blur="$v.item.civility.$touch()"
          />
        </v-col>

        <v-col cols="12" sm="5" md="5">
          <v-text-field
            v-model="item.name"
            :error-messages="nameErrors"
            label="Prénom"
            required
            class="required"
            @input="$v.item.name.$touch()"
            @blur="$v.item.name.$touch()"
          />
        </v-col>

        <v-col cols="12" sm="5" md="5">
          <v-text-field
            v-model="item.lastname"
            :error-messages="lastnameErrors"
            label="Nom"
            required
            class="required"
            @input="$v.item.lastname.$touch()"
            @blur="$v.item.lastname.$touch()"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" sm="6" md="6">
          <v-text-field
            v-model="item.email"
            :error-messages="emailErrors"
            prepend-inner-icon="mdi-email"
            label="Email"
            required
            class="required"
            @input="$v.item.email.$touch()"
            @blur="$v.item.email.$touch()"
          />
        </v-col>

        <v-col cols="12" sm="6" md="6">
          <v-text-field
            v-model="item.phone_number"
            :error-messages="phone_numberErrors"
            prepend-inner-icon="mdi-phone"
            label="Téléphone"
            @input="$v.item.phone_number.$touch()"
            @blur="$v.item.phone_number.$touch()"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" sm="6" md="6">
          <v-text-field
            v-model="item.password"
            :error-messages="passwordErrors"
            prepend-inner-icon="mdi-lock"
            label="Mot de passe"
            :required="isNewUser"
            :class="{'required': isNewUser}"
            type="password"
            counter
            @input="$v.item.password.$touch()"
            @blur="$v.item.password.$touch()"
          />
        </v-col>

        <v-col cols="12" sm="6" md="6">
          <v-text-field
            v-model="item.repeatPassword"
            :error-messages="repeatPasswordErrors"
            prepend-inner-icon="mdi-lock-check"
            label="Confirmation Mot de Passe"
            type="password"
            counter
            @input="$v.item.repeatPassword.$touch()"
            @blur="$v.item.repeatPassword.$touch()"
          />
        </v-col>
      </v-row>

<!--      <v-row>-->
<!--        <v-col cols="12" sm="6" md="6">-->
<!--          <v-text-field-->
<!--            v-model="item.remember_token"-->
<!--            :error-messages="remember_tokenErrors"-->
<!--            label="remember_token"-->
<!--            @input="$v.item.remember_token.$touch()"-->
<!--            @blur="$v.item.remember_token.$touch()"-->
<!--          />-->
<!--        </v-col>-->

<!--        &lt;!&ndash; todo : fix the dateTime format, not supported by default on vuetify &ndash;&gt;-->
<!--        <v-col cols="12" sm="6" md="6">-->
<!--          <InputDate-->
<!--            v-model="item.created_at"-->
<!--            label="created_at"-->
<!--            :error-messages="created_atErrors"-->
<!--          />-->
<!--        </v-col>-->
<!--      </v-row>-->

      <v-row>
<!--        &lt;!&ndash; todo : fix the dateTime format, not supported by default on vuetify &ndash;&gt;-->
<!--        <v-col cols="12" sm="6" md="6">-->
<!--          <InputDate-->
<!--            v-model="item.updated_at"-->
<!--            label="updated_at"-->
<!--            :error-messages="updated_atErrors"-->
<!--          />-->
<!--        </v-col>-->


        <v-col cols="12" sm="5" md="5">
          <v-autocomplete
            v-model="item.antenne"
            v-if="antenneList"
            :items.sync="antenneList"
            :error-messages="antenneErrors"
            label="Antenne"
            item-text="name"
            item-value="@id"
            required
            class="required"
            @input="$v.item.antenne.$touch()"
            @blur="$v.item.antenne.$touch()"
          />
        </v-col>

        <v-col cols="12" sm="5" md="5">
          <v-select
            v-model="item.role"
            :error-messages="roleErrors"
            label="Rôle"
            required
            class="required"
            :items="[{value: 'ROLE_CUSTOMER', text:'Client'}, {value: 'ROLE_DEV', text:'Développeur'}]"
            @input="$v.item.role.$touch()"
            @blur="$v.item.role.$touch()"
          />
        </v-col>

        <v-col cols="12" sm="2" md="2">
          <v-checkbox
            v-model="item.active"
            :error-messages="suspendreErrors"
            label="Suspendre"
            @input="$v.item.active.$touch()"
            @blur="$v.item.active.$touch()"
          />
        </v-col>
      </v-row>

    </v-container>
  </v-form>
</template>

<script>
import has from 'lodash/has';
import { validationMixin } from 'vuelidate';
import { required, requiredIf, sameAs, minLength, email, url } from 'vuelidate/lib/validators'
import { mapActions } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import InputDate from '../InputDate';
import { date } from '../../validators/date';

export default {
  name: 'UserForm',
  mixins: [validationMixin],
  components: {
    InputDate,
  },
  props: {
    values: {
      type: Object,
      required: true
    },

    errors: {
      type: Object,
      default: () => {}
    },

    initialValues: {
      type: Object,
      default: () => {}
    }
  },
  mounted() {
  },
  data: () => ({
      photo: null,
      civility: null,
      suspendre: null,
      name: null,
      lastname: null,
      email: null,
      phone_number: null,
      email_verified_at: null,
      remember_token: null,
      created_at: null,
      updated_at: null,
      role: null,
      antenne: null,
  }),
  computed: {
    ...mapFields('antenne', {
      antenneList: 'selectItems',
    }),

    isNewUser() {
      return (typeof this.item['@id'] === 'undefined')
    },

    // eslint-disable-next-line
    item() {
      return this.initialValues || this.values;
    },

    photoErrors() {
      const errors = [];

      if (!this.$v.item.photo.$dirty) return errors;

      has(this.violations, 'photo') && errors.push(this.violations.photo);


      return errors;
    },
    civilityErrors() {
      const errors = [];

      if (!this.$v.item.civility.$dirty) return errors;

      has(this.violations, 'civility') && errors.push(this.violations.civility);

      !this.$v.item.civility.required && errors.push('La civilité est requise')

      return errors;
    },
    suspendreErrors() {
      const errors = [];

      if (!this.$v.item.suspendre.$dirty) return errors;

      has(this.violations, 'suspendre') && errors.push(this.violations.suspendre);


      return errors;
    },
    nameErrors() {
      const errors = [];

      if (!this.$v.item.name.$dirty) return errors;

      has(this.violations, 'name') && errors.push(this.violations.name);

      !this.$v.item.name.required && errors.push('Le prénom est requis')

      return errors;
    },
    lastnameErrors() {
      const errors = [];

      if (!this.$v.item.lastname.$dirty) return errors;

      has(this.violations, 'lastname') && errors.push(this.violations.lastname);

      !this.$v.item.lastname.required && errors.push('Le nom est requis')

      return errors;
    },
    emailErrors() {
      const errors = [];

      if (!this.$v.item.email.$dirty) return errors;

      has(this.violations, 'email') && errors.push(this.violations.email);

      !this.$v.item.email.required && errors.push('L\'email est requis')
      !this.$v.item.email.email && errors.push('L\'email n\'est pas valide')

      return errors;
    },
    phone_numberErrors() {
      const errors = [];

      if (!this.$v.item.phone_number.$dirty) return errors;

      has(this.violations, 'phone_number') && errors.push(this.violations.phone_number);

      return errors;
    },
    passwordErrors() {
      const errors = [];

      if (!this.$v.item.password.$dirty) return errors;

      has(this.violations, 'password') && errors.push(this.violations.password);

      !this.$v.item.password.required && errors.push('Le mot de passe est requis')
      !this.$v.item.password.minLength && errors.push('Un minimum de 4 caractères est requis')

      return errors;
    },
    repeatPasswordErrors () {
      const errors = []

      if (!this.$v.item.repeatPassword.$dirty) { return errors }

      has(this.violations, 'repeatPassword') && errors.push(this.violations.repeatPassword)

      !this.$v.item.repeatPassword.sameAsPassword && errors.push('Le mot de passe ne correspond pas')

      return errors
    },
    roleErrors() {
      const errors = [];

      if (!this.$v.item.role.$dirty) return errors;

      has(this.violations, 'role') && errors.push(this.violations.role);

      !this.$v.item.role.required && errors.push('Le rôle est requis')

      return errors;
    },
    antenneErrors() {
      const errors = [];

      if (!this.$v.item.antenne.$dirty) return errors;

      has(this.violations, 'antenne') && errors.push(this.violations.antenne);

      !this.$v.item.antenne.required && errors.push('L\'antenne est requis')

      return errors;
    },

    violations() {
      return this.errors || {};
    }
  },
  created() {
    this.fetchAllAntenne()
  },
  methods: {
    ...mapActions('antenne', {
      fetchAllAntenne: 'fetchSelectItems',
    }),
  },
  validations: {
    item: {
      civility: {
        required
      },
      suspendre: {
      },
      name: {
        required
      },
      lastname: {
        required
      },
      email: {
        required,
        email,
      },
      phone_number: {
      },
      password: {
        required: requiredIf (function() {
          return this.isNewUser
        }),
        minLength: minLength(4)
      },
      repeatPassword: {
        sameAsPassword: sameAs('password')
      },
      role: {
        required
      },
      antenne: {
        required
      },
    }
  }
};
</script>
