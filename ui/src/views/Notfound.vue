<template >
    <v-main>
        <v-container>
            <v-row>
                <v-col md="6" offset-md="3" class="text-center">
                    <h1 class="text-center text-h2">404</h1>
                    <h2 class="text-center my-5">Sorry, Page Not Found</h2>
                    <v-btn to="/" small :color="settings.color" class="my-4" rounded outlined>
                        <v-icon> mdi-chevron-left</v-icon> Back
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-main>

</template>
  
<script>
import { mapState } from "vuex";
export default {
    name: "Video",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            data: []
        }
    },
    methods: {
        setBreadcrumsData() {
            const datas = {
                type: "informasi",
                items: [
                    {
                        text: 'Beranda',
                        disabled: false,
                        href: '/',
                        color: "#42A5F5"
                    },
                    {
                        text: 'Informasi',
                        disabled: false,
                        href: '/informasi',
                        color: "#42A5F5"
                    },
                    {
                        text: 'Detail Berita',
                        disabled: true,
                        href: 'breadcrumbs_link_2',
                        color: "#BDBDBD"
                    },
                ],

            }
            this.$store.dispatch('breadcrumData', datas);
            console.log(this.$route.params) // outputs 'yay'

        }
    },
    created() {
        this.setBreadcrumsData();
    }
}
</script> 