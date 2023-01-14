<template>
    <div>
        <v-main>
            <v-container>
                <Informations />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Informations from '@/components/C_Informations.vue';

export default {
    name: "NewsDetail",
    components: {
        Informations
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
  