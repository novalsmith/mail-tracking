 
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
        <v-data-table multi-sort :headerProps="headerprops" :headers="headers" :items="listData" :search="search"
            @click:row="rowClick" :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''">

            <template v-slot:item="{ item, index }">
                <tr class="rowColor">
                    <td>{{ index + 1}}</td>
                    <td>{{ item.employeeId }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.placement }}</td>
                    <td>{{ item.position }}</td>
                    <td>{{ item.unitRoleName }}</td>
                </tr>
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
                { text: 'No', value: 'num' },
                { text: 'NIP', value: 'employeeId' },
                { text: 'Nama', value: 'name' },
                { text: 'Penempatan', value: 'placement' },
                { text: 'Jabatan', value: 'position' },
                { text: 'Unit', value: 'unitRoleName' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            }
        }
    },
    methods: {
        async getData() {
            try {
                // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee");
                this.listData = response.data;
                this.$store.dispatch('employee', response.data);
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
            console.log(row);
        },
        rowDeleteClick(row) {
            console.log(row);
        },
        notready() {
            this.alertNotready = true;
        },
        rowClass(item) {
            console.log(item);
            const rowClass = 'rowClass'
            return rowClass;
        }
    },
    created() {
        this.getSettings();
        this.getData();
    },
    computed: {
        ...mapGetters(['user', 'settings']),

    }
}
</script>

<style lang="css">
.rowColor:hover {
    /* `!important` is necessary here because Vuetify overrides this
    - background cyan darken-2
    */
    background: #0097A7 !important;
    color: white;
    cursor: pointer;
}
</style>