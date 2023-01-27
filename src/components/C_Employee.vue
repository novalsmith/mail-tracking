 
<template>
    <div>
        <v-card>
            <v-container>
                <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2" v-model="alertNotready"
                    elevation="2" icon="mdi-information-outline" border="left" dismissible
                    transition="scale-transition">
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
                :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''">

                <template v-slot:item="{ item, index }">
                    <tr class="rowColor" @click="rowClick(item)">
                        <td>{{ index + 1}}</td>
                        <td>{{ item.employeeId }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.position }}</td>
                        <td>{{ item.es2 }}</td>
                        <td>{{ item.es3 }}</td>
                        <td>{{ item.es4 }}</td> 
                        <td>{{ item.roleCode }}</td>
                        <td>{{ item.roleName }}</td>
                        <td>{{ item.level }}</td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar color="cyan darken-2" class="white--text">
                    <v-btn icon dark @click="dialogDetail = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title> {{ detailDataRow.employeeId }} - {{
                        detailDataRow.name
                    }}</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-container>
                    <v-main class="my-5">
                        <h3> <v-icon class="mx-3">mdi-file-outline</v-icon> Role Access</h3>
                        <form class="my-10">


                            <v-row>
                                <v-col md="4">
                                    <v-text-field label="Ubah Password"></v-text-field>
                                </v-col>
                                <v-col md="4">
                                    <v-select :items="listLevelLookup" v-model="selectedRoleValue" item-text="name"
                                        item-value="code" label="Role"></v-select>
                                </v-col>
                                <v-col md="4">
                                    <v-select :items="statusList" v-model="selectedStatusValue" item-text="name"
                                        item-value="code" label="Status"></v-select>
                                </v-col>
                                <v-col md="12">
                                    <v-btn class="mr-4 white--text" color="cyan darken-2" @click="submit">
                                        <v-icon>mdi-check</v-icon> Submit
                                    </v-btn>
                                    <v-btn text class="mr-4 white--text" color="blue-grey" @click="clear">
                                        <v-icon>mdi-cached</v-icon> Clear
                                    </v-btn>
                                </v-col>
                            </v-row>

                        </form>
                    </v-main>
                    <v-divider></v-divider>
                    <v-list three-line subheader class="my-5">
                        <h3> <v-icon class="mx-3">mdi-account</v-icon> Employee Detail </h3>

                        <v-list-item>
                            <v-list-item-content>
                                <!-- <v-list-item-title> {{ detailDataRow.employeeId }} - {{ detailDataRow.name }}
                                </v-list-item-title> -->
                                <v-list-item-title> Jabatan </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.position }}</v-list-item-subtitle>
                                <v-list-item-title> ES II </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.es2 }}</v-list-item-subtitle>
                                <v-list-item-title> ES III </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.es3 }}</v-list-item-subtitle>
                                <v-list-item-title> ES IV </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.es4 }}</v-list-item-subtitle>
                                <v-list-item-title> Kode Unit Organisasi
                                </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.unitCode }}</v-list-item-subtitle>
                                <v-list-item-title> Kode </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.roleCode }}</v-list-item-subtitle>
                                <v-list-item-title> Role </v-list-item-title>
                                <v-list-item-subtitle class="mb-3">{{ detailDataRow.roleName }}</v-list-item-subtitle>
                                <v-list-item-title> Level </v-list-item-title>
                                <v-list-item-subtitle>{{ detailDataRow.level }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>

                    </v-list>
                </v-container>

            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            dialogDetail: false,
            detailDataRow: [],
            alertNotready: false,
            search: "",
            isLoading: true,
            listData: [],
            listLevelLookup: [],
            selectedRoleValue: "",
            selectedStatusValue: "",
            statusList: [
                {
                    code: "ACTIVE",
                    name: "Aktif"
                },
                {
                    code: "INACTIVE",
                    name: "Tidak Aktif"
                }
            ],
            roleList: [
                {
                    code: "DJB",
                    name: "Direktur Jenderal"
                },
                {
                    code: "SDB",
                    name: "Sekretaris Ditjen"
                },
                ,
                {
                    code: "SDB.1",
                    name: "Staf"
                }
            ],
            headers: [
                { text: 'No', value: 'num' },
                { text: 'NIP', value: 'employeeId' },
                { text: 'Nama', value: 'name' },
                { text: 'Jabatan', value: 'position' },
                { text: 'ES II', value: 'es2' },
                { text: 'ES III', value: 'es3' },
                { text: 'ES IV', value: 'es4' }, 
                { text: 'Kode', value: 'roleCode' },
                { text: 'Role', value: 'roleName' },
                { text: 'Level', value: 'level' }
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
                // var responseLookup = await axios.get(process.env.VUE_APP_SERVICE_URL + "lookup/LEVEL");
                var lookupData = JSON.parse(localStorage.getItem('lookups'));
                // mapping header
                var responseLookup = lookupData.filter(val => val.type == "LEVEL").map(result => { return result; });

                this.listLevelLookup = responseLookup.data;
                // console.log(responseLookup);
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
            this.dialogDetail = true;
            this.detailDataRow = row;
            this.selectedRoleValue = row.level;
            this.selectedStatusValue = row.status;
            console.log(this.detailDataRow);
            console.log(this.listLevelLookup);
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
        },
        submit() {
            // this.$v.$touch()
        },
        clear() {
            // this.$v.$reset() 
        },
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