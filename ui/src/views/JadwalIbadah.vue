<template>
    <div>
        <v-main>
            <v-container>
                <Jadwal />
            </v-container>
        </v-main>
    </div>
</template>
  
<script>
// @ is an alias to /src
import Jadwal from '@/components/C_JadwalIbadah';

export default {
    name: "JadwalIbadah",
    components: {
        Jadwal
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
  