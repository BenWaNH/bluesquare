<template>
  <div class="user-list">
    <v-container grid-list-xl fluid>
<!--      <v-layout row wrap>-->
        <v-flex lg12>
          <v-data-table
            v-model="selected"
            :headers="headers"
            :items="items"
            :items-per-page.sync="options.itemsPerPage"
            :loading="isLoading"
            loading-text="Loading..."
            :options.sync="options"
            :server-items-length="totalItems"
            class="elevation-1"
            item-key="@id"
            hide-default-footer
            disable-sort
            @update:options="onUpdateOptions"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Utilisateurs</v-toolbar-title>

                <v-spacer></v-spacer>


                <v-btn
                  color="primary"
                  dark
                  class="ml-2"
                  @click="addHandler"
                >
                  New Item
                </v-btn>
              </v-toolbar>
            </template>

            <template v-if="item.email" slot="item.email" slot-scope="{ item }">
              <a :href="'mailto:' + item.email">
                {{ item.email }}
              </a>
            </template>

            <template v-if="item.role" slot="item.role" slot-scope="{ item }">
              {{ roles.get(item.role) }}
            </template>

            <template v-if="item.antenne" slot="item.antenne" slot-scope="{ item }">
              <nuxt-link :to="'antennes/' + encodeURIComponent(item.antenne['@id'])">
                {{ item.antenne.name }}
              </nuxt-link>
            </template>

            <ActionCell
              slot="item.action"
              slot-scope="props"
              :handle-edit="() => editHandler(props.item)"
              :handle-delete="() => deleteHandler(props.item)"
            ></ActionCell>
          </v-data-table>
        </v-flex>
<!--      </v-layout>-->
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { mapFields } from 'vuex-map-fields';
import list from '../../mixins/list';

export default {
  servicePrefix: 'users',
  mixins: [list],
  components: {
    Toolbar: () => import('../../components/Toolbar'),
    ActionCell: () => import('../../components/ActionCell'),
    UserFilterForm: () => import('../../components/user/Filter'),
    DataFilter: () => import('../../components/DataFilter')
  },
  data: () => ({
    headers: [
      { text: 'Prénom', value: 'firstName' },
      { text: 'Nom', value: 'lastName' },
      { text: 'Email', value: 'email' },
      { text: 'Rôle', value: 'role' },
      {
        text: 'Actions',
        value: 'action',
        sortable: false
      }
    ],
    selected: [],
    roles: new Map([['ROLE_CUSTOMER', 'Client'], ['ROLE_DEV', 'Développeur']]),
  }),
  computed: {
    ...mapGetters('user', {
      items: 'list'
    }),
    ...mapFields('user', {
      deletedItem: 'deleted',
      error: 'error',
      isLoading: 'isLoading',
      resetList: 'resetList',
      totalItems: 'totalItems',
      view: 'view'
    })
  },
  methods: {
    ...mapActions('user', {
      fetchAll: 'fetchAll',
      deleteItem: 'del'
    }),
  }
};
</script>
