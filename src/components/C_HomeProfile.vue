<template>

    <v-row>
        <v-col lg="12">
            <h1 class="font-weight-regular" :color="color">Welcome to <br> Mail Track Minerba</h1>
            <p class="description" :color="color">LSebuah tools yang dirancang untuk membantu proses penelusuran
                (tracking) terhadap persuratan di lingkungan internal Direktorat Jenderal Mineral dan Batubara</p>

        </v-col>
    </v-row>


</template>

<script>
import { mapState } from "vuex";
export default {
    name: "Hero",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            color: "grey darken-2"
        }
    }
}
</script>
 
<style>
.intro-text {
    font-size: 40px;
    font-weight: 600;
    font-family: 'Roboto';
    line-height: 40pt;
    /* color: #444444ba; */
}

p.description {
    font-size: 19px;
    margin: 32px 0px;
    /* color: #444444ba; */

}

/* 
.img-circle {
    border-radius: 3%;
} */
</style>