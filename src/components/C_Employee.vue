
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Employee</h1>
        </div>
        <v-card class="my-5">
            <v-card-title>Filter

            </v-card-title>
            <v-form>
                <v-container>
                    <v-row>

                        <v-col cols="12" md="4">
                            <v-text-field dense outlined clearable label="NIP" required></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field dense outlined clearable label="Nama" required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field dense label="Jabatan" outlined clearable required></v-text-field>
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
            <v-container>
                <v-alert text dense close-icon="mdi-close-circle-outline" :color="responseAlert.color"
                    v-model="isShowAlert" elevation="2" icon="mdi-information-outline" border="left" dismissible
                    transition="scale-transition">
                    {{ responseAlert.message }}
                </v-alert>
            </v-container>
            <v-data-table multi-sort :headerProps="headerprops" :headers="headers" :items="listData"
                :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''" :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
                <template v-slot:item.num="{ index, item }">
                    {{ index+ 1 }}
                </template>
            </v-data-table>
        </v-card>
    </v-container>
</template>


<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            responseAlert: {
                message: "",
                color: ""
            },
            responseAlertReview: {
                message: "",
                color: ""
            },
            isShowAlert: false,
            dialogReview: false,
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
        searching() { }
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