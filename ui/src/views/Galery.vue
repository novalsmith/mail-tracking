<template>
    <div>
        <v-main>
            <v-container>
                <Galery />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Galery from '@/components/C_AllGalery.vue';

export default {
    name: "NewsDetail",
    components: {
        Galery
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
  