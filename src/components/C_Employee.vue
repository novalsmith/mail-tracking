 
<template>
    <v-card>
        <v-container>
            <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2" v-model="alertNotready"
                elevation="2" icon="mdi-information-outline" border="left" dismissible transition="scale-transition">
                Sorry, this feature is not ready yet - <strong>Under Maintenance!</strong>
            </v-alert>
        </v-container>
        <v-card-title>
            Employee
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                hide-details></v-text-field>
            <v-spacer></v-spacer>
            <v-btn @click="notready" small color="cyan darken-2" class="white--text"> <v-icon>mdi-plus</v-icon>
                Add</v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="listData" :search="search" @click:row="rowClick">
            <template v-slot:item.num="{ index }">
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
                { text: 'No', value: 'num' },
                { text: 'NIP', value: 'employeeId' },
                { text: 'Nama', value: 'name' },
                { text: 'Penempatan', value: 'placement' },
                { text: 'Jabatan', value: 'position' },
                { text: 'Unit', value: 'unitRoleName' },
                { text: 'Actions', value: 'actions', sortable: false }
            ],
        }
    },
    methods: {
        async getData() {
            try {
                var response = await axios.get("employee");
                this.listData = response.data;
                this.$store.dispatch('employee', response.data);
            } catch (error) {
                console.log(error.response);

            }
        },
        async getSettings() {

            this.$store.dispatch('settings', this.themeColoring);
        },
        rowClick(row) {
            console.log(row);
            this.notready();
        },
        rowEditClick(row) {
            console.log(row);
        },
        rowDeleteClick(row) {
            console.log(row);
        },
        notready() {
            this.alertNotready = true;
        }
    },
    created() {
        this.getSettings();
        this.getData();
    },
    computed: {
        ...mapGetters(['user', 'settings'])
    }
}
</script>