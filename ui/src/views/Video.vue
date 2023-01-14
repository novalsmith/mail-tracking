<template>
    <div>
        <v-main>
            <v-container>
                <Videos />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Videos from '@/components/C_AllVideos.vue';

export default {
    name: "Video",
    components: {
        Videos
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
  