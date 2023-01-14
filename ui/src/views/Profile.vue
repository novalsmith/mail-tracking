<template>
    <div>
        <v-main>
            <v-container>
                <Profile />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Profile from '@/components/C_ProfileDetail.vue';

export default {
    name: "NewsDetail",
    components: {
        Profile
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
  