<template >
    <v-main>
        <v-container>
            <v-row>
                <v-col md="6" offset-md="3" class="text-center">
                    <h1 class="text-center">404</h1>
                    <h2 class="text-center">Maaf, halaman tidak ditemukan</h2>
                    <v-btn small color="teal my-5" to="/">Kembali</v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-main>

</template>
  
<script>
import { mapState } from "vuex";
import Breadcrumbs from '@/components/C_Breadcrumbs.vue';
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
    components: {
        Breadcrumbs
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