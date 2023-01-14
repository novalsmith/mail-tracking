<template >

    <C_Skeleton />


</template>
  
<script>
import { mapState } from "vuex";
import C_Skeleton from '@/components/C_Skeleton.vue';
export default {
    name: "C_Skeleton",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            data: []
        }
    },
    components: {
        C_Skeleton
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