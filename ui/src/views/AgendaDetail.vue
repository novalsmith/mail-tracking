<template>
    <div>
        <v-main>
            <v-container>
                <AgendaDetails />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import AgendaDetails from '@/components/C_AgendaDetail.vue';

export default {
    name: "NewsDetail",
    components: {
        AgendaDetails
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
  