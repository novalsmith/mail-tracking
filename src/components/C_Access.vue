 
<template>
    <v-card>
        <v-container>
            <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2" v-model="alertNotready"
                elevation="2" icon="mdi-information-outline" border="left" dismissible transition="scale-transition">
                Sorry, this feature is not ready yet - <strong>Under Maintenance!</strong>
            </v-alert>
        </v-container>
        <v-card-title>
            Access
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                hide-details></v-text-field>
            <v-spacer></v-spacer>
            <v-btn @click="notready" small color="cyan darken-2" class="white--text"> <v-icon>mdi-plus</v-icon>
                Add</v-btn>
        </v-card-title>
        <v-data-table multi-sort :headerProps="headerprops" class="row-pointer" :headers="headers" :items="listData"
            :search="search" @click:row="rowClick" :loading="isLoading"
            :loading-text="isLoading ? 'Loading... Please wait' : ''">
            <template v-slot:item="{ item, index }">
                <tr class="rowColor">
                    <td>{{ index + 1}}</td>
                    <td>{{ item.employeeId }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.statusName }}</td>
                    <td>{{ item.roleLevelName }}</td>
                </tr>
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
            isLoading: true,
            listData: [],
            headers: [
                { text: 'No', value: 'accessid' },
                { text: 'NIP', value: 'employeeId' },
                { text: 'Nama', value: 'name' },
                { text: 'Status', value: 'statusName' },
                { text: 'Role', value: 'roleLevelName' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            }
        }
    },
    methods: {
        async getUsers() {
            try {
                // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "access");
                this.listData = response.data;
                this.$store.dispatch('access', response.data);
                this.isLoading = false;
            } catch (error) {
                console.log(error.response);
                this.isLoading = false;
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

        },
        rowDeleteClick(row) {

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
        ...mapGetters(['access', 'settings'])
    }
}
</script>

<style lang="css" scoped>
.rowColor:hover {
    /* `!important` is necessary here because Vuetify overrides this
    - background cyan darken-2
    */
    background: #0097A7 !important;
    color: white;
    cursor: pointer;
}
</style>