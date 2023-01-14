<template>
    <v-row class="my-15">
        <v-col md="12">
            <h2 class="font-weight-regular">Lainnya</h2>
        </v-col>
        <v-col md="4" v-for="( item, i ) in listData" :key="i">
            <v-card>
                <router-link :to="'/informasi/detail/' + item.slugTitle">
                    <v-img height="250" src="https://cdn.vuetifyjs.com/images/cards/cooking.png"></v-img>
                </router-link>


                <v-row>
                    <v-col md="6">
                        <v-chip class="mx-5 my-5 white--text" small :color="settings.color"
                            :to="'/informasi/kategori/' + item.slugCategory">
                            {{ item.category }}
                        </v-chip>
                    </v-col>
                    <v-col md="6">
                        <v-card-subtitle class="float-right">
                            {{ item.date }}
                        </v-card-subtitle>
                    </v-col>
                </v-row>


                <v-card-title class="font-weight-regular">{{ item.title }}</v-card-title>

            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "News",
    computed: {
        ...mapState(['settings'])
    },
    data: () => ({
        listData: [
            {
                id: "23453",
                category: "PKB",
                slugCategory: "pkb",
                slugTitle: "ibadah-natal-2021",
                title: "Ibadah Natal 2021",
                date: "25 Desember 2021"
            },
            {
                id: "2366",
                category: "PW",
                slugCategory: "pw",
                slugTitle: "ibadah-natal-2021",
                title: "Pesparawi Nasional diadakan di maybrat",
                date: "25 September 2022"
            },
            {
                id: "2553",
                category: "Jemaat",
                slugCategory: "jemaat",
                slugTitle: "ibadah-natal-2021",
                title: "Peresmian Gedung Gereja Cabang di Maybrat",
                date: "25 September 2022"
            },
            {
                id: "23499",
                category: "Natal",
                slugCategory: "natal",
                slugTitle: "ibadah-natal-2021",
                title: "Sidang Klasis di Maybrat",
                date: "25 September 2022"
            },
            {
                id: "2888",
                category: "Natal",
                slugCategory: "sidang-klasis",
                slugTitle: "sidang-klasis-di-maybrat",
                title: "Sidang Klasis di Maybrat",
                date: "25 September 2022"
            },
            {
                id: "2345",
                category: "Natal",
                slugCategory: "natal",
                slugTitle: "ibadah-natal-2021",
                title: "Sidang Klasis di Maybrat",
                date: "25 September 2022"
            }
        ]
    }),
}
</script>