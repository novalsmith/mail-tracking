<template>
  <div>
    <v-main>
      <v-container>
        <Agenda />
      </v-container>
    </v-main>
  </div>
</template>

<script>
// @ is an alias to /src
import Agenda from '@/components/C_Agenda.vue';

export default {
  name: "AgendaDetail",
  components: {
    Agenda
  },
  mounted() {
    this.toTop();
  },
  methods: {
    toTop() {
      this.$vuetify.goTo(0)
    }
  }

}
</script>
