<template>
    <v-main>
        <img width="100%" height="100%"
            :src="require(`../assets/${this.$store.state.settings['dialogData']?.data?.source}`)"
            :alt="this.$store.state.settings['dialogData']?.data?.alt">
    </v-main>
</template>

<script>
import { mapState } from "vuex";

export default {
    data() {
        return {
            dialogm1: '',
        }
    },
    methods: {

    },
    computed: {
        ...mapState(['settings'])
    },
}
</script>