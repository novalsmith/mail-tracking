<template>
    <div>
        <v-main>
            <v-container>
                <Detail />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Detail from '@/components/C_InformationDetail';

export default {
    name: "InformationDetail",
    components: {
        Detail
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
  