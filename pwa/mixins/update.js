import notification from "./notification";
import { formatDateTime } from "../utils/dates";

export default {
  mixins: [notification],
  data() {
    return {
      item: {},
    };
  },
  created() {
    this.retrieve(decodeURIComponent(this.$route.params.id));
  },
  beforeDestroy() {
    this.reset();
  },
  computed: {
    retrieved() {
      return this.find(decodeURIComponent(this.$route.params.id));
    },
  },
  methods: {
    del() {
      this.deleteItem(this.retrieved).then(() => {
        this.showMessage(`${this.item["@id"]} deleted.`);
        this.$router
          .push({ name: `${this.$options.servicePrefix}` })
          .catch(() => {});
      });
    },
    formatDateTime,
    reset() {
      this.$refs.updateForm.$v.$reset();
      this.updateReset();
      this.delReset();
      this.createReset();
    },

    onSendForm() {
      const updateForm = this.$refs.updateForm;
      updateForm.$v.$touch();

      if (!updateForm.$v.$invalid) {
        this.update(updateForm.$v.item.$model);
      }
    },

    resetForm() {
      this.$refs.updateForm.$v.$reset();
      this.item = { ...this.retrieved };
    },
  },
  watch: {
    deleted(deleted) {
      if (!deleted) {
        return;
      }
      this.$router
        .push({ name: `${this.$options.servicePrefix}` })
        .catch(() => {});
    },

    error(message) {
      message && this.showError(message);
    },

    deleteError(message) {
      message && this.showError(message);
    },

    updated(val) {
      this.showMessage(`${val["name"] ? val["name"] : val['@id']} updated.`);
    },

    retrieved(val) {
      this.item = { ...val };
    },
  },
};
