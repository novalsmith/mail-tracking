<template>
    <div>
        <v-main>
            <v-container>
                <Contact />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Contact from '@/components/C_Contact.vue';

export default {
    name: "DocumentDetail",
    components: {
        Contact
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
  