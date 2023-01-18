 
<template>
    <v-card>
        <v-container>
            <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2" v-model="alertNotready"
                elevation="2" icon="mdi-information-outline" border="left" dismissible transition="scale-transition">
                Sorry, this feature is not ready yet - <strong>Under Maintenance!</strong>
            </v-alert>
        </v-container>
        <v-card-title>
            Nadine
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                hide-details></v-text-field>
            <v-spacer></v-spacer>
            <v-btn @click="notready" small color="cyan darken-2 mx-2" class="white--text"> <v-icon>mdi-upload</v-icon>
                Upload</v-btn>
            <v-btn @click="notready" small color="cyan darken-2" class="white--text"> <v-icon>mdi-download</v-icon>
                Template</v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="listData" :search="search" @click:row="rowClick">
            <template v-slot:item.trackingid="{ index }">
                {{ index + 1 }}
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="rowEditClick(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="rowDeleteClick(item)">
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            alertNotready: false,
            search: "",
            listData: [],
            headers: [
                { text: 'No', value: 'trackingid' },
                { text: 'Agenda', value: 'agendaNumber' },
                { text: 'Terima', value: 'receiptDate' },
                { text: 'Tanggal Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'typeName' },
                { text: 'Dari', value: 'fromName' },
                { text: 'Kepada', value: 'toName' },
                { text: 'Ket', value: 'descriptionName' },
                // { text: 'Status', value: 'isDone' },
                { text: 'Actions', value: 'actions', sortable: false }
            ],
        }
    },
    methods: {
        async getUsers() {
            try {
                var response = await axios.get("tracking");

                this.listData = response.data;
                var response2 = await axios.get("tracking/SDB.L");


                this.$store.dispatch('tracking', response.data);
            } catch (error) {
                console.log(error.response);

            }
        },
        async getSettings() {

            this.$store.dispatch('settings', this.themeColoring);
        },
        rowClick(row) {
            this.notready();
        },
        rowEditClick(row) {
            // this.notready();
        },
        rowDeleteClick(row) {
            // this.notready();
        },
        notready() {
            this.alertNotready = true;
        }
    },
    created() {
        this.getSettings();
        this.getUsers();
    },
    computed: {
        ...mapGetters(['tracking', 'settings'])
    }
}
</script>