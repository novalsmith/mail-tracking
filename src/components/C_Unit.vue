 
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Unit</h1>
        </div>
        <v-card class="my-5">
            <v-card-title>Filter

            </v-card-title>

            <v-form>
                <v-container>
                    <v-row>

                        <v-col cols="12" md="6">
                            <!-- <v-select clearable :items="latterType" item-text="name" item-value="code"
                                v-model="filter.sifatSurat" dense outlined label="Sifat Surat" multiple chips>
                                <template v-slot:prepend-item>
                                    <v-list-item ripple @mousedown.prevent @click="toggle">
                                        <v-list-item-action>
                                            <v-icon
                                                :color="filter.sifatSurat.length > 0 ? 'cyan darken-2' : 'cyan darken-2'">
                                                {{ icon }}
                                            </v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                Select All
                                            </v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider class="mt-2"></v-divider>
                                </template>
                            </v-select> -->
                            <v-text-field dense outlined clearable v-model="filter.sifatSurat" label="Kode/Unit"
                                required></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field dense outlined clearable v-model="filter.noAgenda" label="Jabatan"
                                required></v-text-field>
                        </v-col>
                        <!-- <v-col cols="12" md="4">
                            <v-text-field dense label="Nomor Surat" v-model="filter.noSurat" outlined clearable
                                required></v-text-field>
                        </v-col> -->
                        <!-- show if is advanced search -->

                        <!-- show if is advanced search -->
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
                <v-alert text dense close-icon="mdi-close-circle-outline" :color="responseAlert.color" v-model="isShowAlert"
                    elevation="2" icon="mdi-information-outline" border="left" dismissible transition="scale-transition">
                    {{ responseAlert.message }}
                </v-alert>
            </v-container>
            <v-data-table multi-sort :headerProps="headerprops" :headers="headers" :items="listData" :search="search"
                @click:row="rowClick" :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''">
                <template v-slot:item="{ item, index }">
                    <tr class="rowColor">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.code }}</td>
                        <td>{{ item.unitCodeOrg }}</td>
                        <td>{{ item.oraganizationUnit }}</td>
                        <td>{{ item.positions }}</td>
                        <td>{{ item.statusName }}</td>

                    </tr>
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
            alertNotready: false,
            search: "",
            isLoading: true,
            listData: [],
            headers: [
                { text: 'No', value: 'clasificationId' },
                { text: 'Kode Jabatan', value: 'code' },
                { text: 'Kode Unit', value: 'unitCodeOrg' },
                { text: 'Unit Organisasi', value: 'oraganizationUnit' },
                { text: "Jabatan", value: 'positions' },
                { text: 'Status', value: 'statusName' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            },
            tabs: null,
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            isShowAlertDialogDetail: false,
            isAdvanceSearch: false,
            isShowTable: false,
            dateAction: null,
            menuDate: false,
            modalDate: false,
            dialogDetail: false,
            isShowAlert: false,
            search: "",
            inboxListData: [],
            detailDataRow: [],
            detailDataList: [],
            allTrackingData: [],
            dari: '',
            select: null,
            userData: [],
            token: "",
            show1: false,
            password: '',
            alert: false,
            response: {
                fail: ""
            },
            userDefault: "",
            isReciverShow: false,
            listItemsReciver: [],
            isLoading: false,
            responseAlert: {
                message: "",
                color: ""
            },
            filter: {
                sifatSurat: [],
                noAgenda: "",
                noSurat: "",
                dari: "",
                kepada: [],
                keterangan: [],
                tglTerimaStart: "",
                tglTerimaEnd: "",
                tglSuratStart: "",
                tglSuratEnd: ""
            },
        }
    },
    methods: {
        async getUsers() {
            try {
                // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "unit");
                this.listData = response.data;
                this.$store.dispatch('unit', response.data);
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
        }
    },
    created() {
        this.getSettings();
        this.getUsers();
    },
    computed: {
        ...mapGetters(['unit', 'settings'])
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

/* h1 {
    -webkit-text-stroke: 0.8px #fff;
} */
</style>