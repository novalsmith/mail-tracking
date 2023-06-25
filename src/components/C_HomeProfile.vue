<template>
    <v-row>
        <v-col lg="12">
            <h1 class="font-weight-medium" :color="color">Welcome to Mail Track Minerba</h1>
            <span class="description" :color="color">Sebuah tools yang dirancang untuk membantu proses penelusuran
                (tracking) terhadap persuratan di lingkungan internal Direktorat Jenderal Mineral dan Batubara</span>

        </v-col>
        <v-col cols="4">
            <v-container>
                <v-card class="my-5">
                    <v-card-title>Inbox
                    </v-card-title>
                    <v-card-text>
                        <h1>35</h1>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>
        <v-col cols="4">
            <v-container>
                <v-card class="my-5">
                    <v-card-title>Outbox
                    </v-card-title>
                    <v-card-text>
                        <h1>35</h1>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>
        <v-col cols="4">
            <v-container>
                <v-card class="my-5">
                    <v-card-title>Nadine
                    </v-card-title>
                    <v-card-text>
                        <h1>300</h1>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>

        <v-col lg="12">
            <v-container>
                <v-card class="my-5">
                    <v-card-title>Lacak Surat

                    </v-card-title>
                    <v-form>
                        <v-container>
                            <v-row>

                                <v-col cols="12">
                                    <v-text-field v-model="searchValue" dense outlined clearable label="Search"
                                        required></v-text-field>
                                </v-col>

                            </v-row>
                        </v-container>
                    </v-form>
                    <v-card-actions>
                        <v-btn color="cyan darken-2" dark @click="searching">
                            <v-icon>mdi-magnify</v-icon> Search
                        </v-btn>
                        <v-btn text class="mr-4 white--text" color="blue-grey" @click="clear">
                            <v-icon>mdi-cached</v-icon> Clear
                        </v-btn>
                    </v-card-actions>

                </v-card>
            </v-container>

        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';
import { mapState } from "vuex";
export default {
    name: "Hero",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            color: "grey darken-2",
            searchValue: ""
        }
    },
    methods: {
        async getData() {
            try {
                await axios.get(process.env.VUE_APP_SERVICE_URL + "employee");
            } catch (error) {
                console.log(error);
                this.isLoading = false;
            }
        }
    },
    created() {
        var listData = JSON.parse(localStorage.getItem('userData'));
        this.users = listData != undefined && listData.user ? listData.user : [];
        this.getData();
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

/* h1 {
  -webkit-text-stroke: 0.8px #fff;
} */

/* 
.img-circle {
    border-radius: 3%;
} */
</style>