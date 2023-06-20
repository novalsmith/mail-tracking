
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
                            <v-text-field v-model="filter.employeeIdValue" dense outlined clearable label="NIP"
                                required></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field v-model="filter.nameValue" dense outlined clearable label="Nama"
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="filter.jabatanValue" dense label="Jabatan" outlined clearable
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="filter.kodeValue" dense label="Kode" outlined clearable
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="filter.levelValue" dense label="Level" outlined clearable
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
            <v-container>
                <v-alert text dense close-icon="mdi-close-circle-outline" :color="responseAlert.color" v-model="isShowAlert"
                    elevation="2" icon="mdi-information-outline" border="left" dismissible transition="scale-transition">
                    {{ responseAlert.message }}
                </v-alert>
            </v-container>
            <v-data-table multi-sort :headerProps="headerprops" :headers="headers" :items="listData" :loading="isLoading"
                @click:row="rowClick" class="mx-3 table-style" :loading-text="isLoading ? 'Loading... Please wait' : ''"
                :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
                <template v-slot:item.num="{ index, item }">
                    {{ index + 1 }}
                </template>
            </v-data-table>
            <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <!-- <v-overlay v-if="isOverlayLoading" class="align-center justify-center">
                        <v-progress-circular color="white" indeterminate size="64" width="7"></v-progress-circular>
                    </v-overlay> -->

                    <v-toolbar color="cyan darken-2" class="white--text">

                        <v-btn icon dark @click="dialogDetail = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Detail Employee</v-toolbar-title>
                        <v-spacer></v-spacer>

                    </v-toolbar>
                    <v-container>
                        <!-- <v-row>
                            <v-col md="7">
                                <div class="my-5">
                                    <h3>Riwayat Jabatan</h3>

                                    <v-divider></v-divider>
                                </div>
                                <div>
                                    <h1>Employee list</h1>
                                </div>
                            </v-col>
                            <v-col md="5">
                                <div class="my-5">
                                    <h3>Employee</h3>
                                    <v-divider></v-divider>
                                </div>
                            </v-col>
                        </v-row> -->

                        <template>
                            <v-card>
                                <v-tabs v-model="tab" icons-and-text background-color="teal lighten-5">
                                    <v-tabs-slider></v-tabs-slider>

                                    <v-tab href="#tab-1">
                                        Riwayat Jabatan
                                        <v-icon>mdi mdi-history</v-icon>
                                    </v-tab>

                                    <v-tab href="#tab-2">
                                        Employee
                                        <v-icon>mdi mdi-bag-checked</v-icon>
                                    </v-tab>
                                </v-tabs>

                                <v-tabs-items v-model="tab">
                                    <v-tab-item value="tab-1">
                                        <v-card>
                                            <v-col md="12" v-if="isShowAlertDialogDetail">
                                                <v-alert text dense close-icon="mdi-close-circle-outline"
                                                    :color="responseAlert.color" elevation="2"
                                                    icon="mdi-information-outline" border="left" dismissible
                                                    transition="scale-transition">
                                                    {{ responseAlert.message }}
                                                </v-alert>

                                            </v-col>
                                            <v-card-text>
                                                <div class="mx-3">
                                                    <v-row class="my-5">
                                                        <v-col md="6">
                                                            <h2>Riwayat Jabatan</h2>
                                                        </v-col>
                                                        <v-col offset-md="2">
                                                            <v-btn small color="cyan darken-2 mx-3" dark
                                                                @click="rowClickHistoryJabatan">
                                                                <v-icon>mdi-plus</v-icon> Tambah Jabatan
                                                            </v-btn>
                                                            <v-btn small color="green lighten-1" dark @click="reloadData">
                                                                <v-icon>mdi-refresh</v-icon> Refresh
                                                            </v-btn>
                                                        </v-col>
                                                    </v-row>
                                                    <v-data-table multi-sort :headerProps="headerprops"
                                                        :headers="headerRiwayatJabatan" :items="listDataRiwajatJabatan"
                                                        :loading="isLoadingHistoryJabatan"
                                                        @click:row="rowClickHistoryJabatan" class="mx-3 table-style"
                                                        :loading-text="isLoadingHistoryJabatan ? 'Loading... Please wait' : ''"
                                                        :footer-props="{
                                                            showFirstLastPage: true,
                                                            firstIcon: 'mdi-arrow-collapse-left',
                                                            lastIcon: 'mdi-arrow-collapse-right',
                                                            prevIcon: 'mdi-minus',
                                                            nextIcon: 'mdi-plus'
                                                        }">
                                                        <template v-slot:item.num="{ index, item }">
                                                            {{ index + 1 }}
                                                        </template>
                                                    </v-data-table>
                                                </div>
                                            </v-card-text>
                                        </v-card>
                                    </v-tab-item>
                                    <v-tab-item value="tab-2">
                                        <v-card>
                                            <v-card-text>Tab Detail</v-card-text>
                                        </v-card>
                                    </v-tab-item>
                                </v-tabs-items>
                            </v-card>
                        </template>

                        <!-- <v-row justify="center"> -->
                        <v-dialog v-model="dialogJabatan" max-width="750px">
                            <v-card>
                                <v-card-title class="dragable">
                                    <span class="text-h5">Jabatan</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-form>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12">

                                                    <v-select required :items="modalJabatanFields.selectedjabatan"
                                                        v-model="modalJabatanFields.roleCode" item-text="positionName"
                                                        item-value="code" dense outlined label="Jabatan"></v-select>
                                                </v-col>
                                                <v-col cols="8">
                                                    <v-dialog ref="dialogmodalDateTglTerima"
                                                        :return-value.sync="modalJabatanFields.modalDateTglTerima"
                                                        persistent width="290px">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-text-field outlined dense label="Masa Jabatan"
                                                                prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                v-on="on" v-model="dateRangeText"></v-text-field>
                                                        </template>
                                                        <v-date-picker dense v-model="modalJabatanFields.dateActionTerima"
                                                            range type="date" scrollable>
                                                            <v-spacer></v-spacer>

                                                            <v-btn text color="primary"
                                                                @click="$refs.dialogmodalDateTglTerima.save(modalJabatanFields.modalDateTglTerima)">
                                                                OK
                                                            </v-btn>
                                                        </v-date-picker>
                                                    </v-dialog>
                                                </v-col>


                                                <v-col cols="4" v-if="isEdit">
                                                    <v-switch v-model="modalJabatanFields.status" label="Aktif"
                                                        inset></v-switch>

                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-form>
                                </v-card-text>

                                <v-card-actions>
                                    <v-btn color="cyan darken-2" dark @click="saveJabatan()">
                                        <v-icon>mdi-floppy</v-icon> Submit
                                    </v-btn>
                                    <v-btn text class="mr-4 white--text" color="blue-grey" @click="clearJabatan">
                                        <v-icon>mdi-cached</v-icon> Clear
                                    </v-btn>

                                    <v-btn text class="mr-4 white--text" color="blue-grey" @click="dialogJabatan = false">
                                        <v-icon>mdi-times</v-icon> Close
                                    </v-btn>


                                    <!-- <v-spacer></v-spacer> -->
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <!-- </v-row> -->
                    </v-container>
                </v-card>
            </v-dialog>
        </v-card>
    </v-container>
</template>


<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            tab: null,
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
            dialogJabatan: false,
            isLoading: true,
            isLoadingHistoryJabatan: true,
            isOverlayLoading: true,
            listData: [],
            listDataRiwajatJabatan: [],
            listLevelLookup: [],
            selectedRoleValue: "",
            selectedStatusValue: "",
            isEdit: false,
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
            headerRiwayatJabatan: [
                { text: 'No', value: 'num' },
                { text: 'NIP', value: 'employeeId' },
                { text: 'Jabatan', value: 'jabatan' },
                { text: 'Start Date', value: 'startDate' },
                { text: 'End Date', value: 'endDate' },
                { text: 'Unit', value: 'unit' },
                { text: 'Status', value: 'status' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            },
            filter: {
                employeeIdValue: "",
                nameValue: "",
                jabatanValue: "",
                kodeValue: "",
                levelValue: "",
                searchingParams: []
            },
            isShowAlertDialogDetail: false,
            responseAlert: {
                message: "",
                color: ""
            },
            modalJabatanFields: {
                employeeId: "",
                roleCode: "",
                startDate: null,
                endDate: null,
                status: 0,
                modalDateTglTerima: null,
                dateActionTerima: [],
                selectedjabatan: [],
                roleCodeValueEdit: ""
            },
            detailJabatanData: []
        }
    },
    methods: {
        async getEmployee() {
            try {
                // console.log(this.filter.searchingParams);
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee", { params: { searchingParams: this.filter.searchingParams } });


                var lookupData = JSON.parse(localStorage.getItem('lookups'));
                // mapping header
                var responseLookup = lookupData.filter(val => val.type == "LEVEL").map(result => { return result; });

                this.listLevelLookup = responseLookup.data;
                // console.log(responseLookup);
                this.listData = response.data;
                // this.listDataRiwajatJabatan = response.data;
                this.$store.dispatch('employee', response.data);
                this.isLoading = false;
            } catch (error) {
                console.log(error.response);
                this.isLoading = false;
            }
        },
        async getPosition() {
            try {
                // console.log(this.filter.searchingParams);
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee/position");
                // mapping header
                this.modalJabatanFields.selectedjabatan = response.data;
                this.isLoading = false;
            } catch (error) {
                console.log(error.response);
                this.isLoading = false;
            }
        },
        async saveJabatan() {
            if (this.modalJabatanFields.dateActionTerima) {
                if (this.modalJabatanFields.dateActionTerima[1] == undefined || this.modalJabatanFields.dateActionTerima[1] == "") {
                    this.responseAlert.color = 'orange';
                    this.responseAlert.message = "End Date tidak booleh kosong";
                    this.loadingUploadButton = false;
                    this.isShowAlertDialogDetail = true;
                    this.dialogJabatan = false;
                    return;
                }
                this.modalJabatanFields.startDate = this.modalJabatanFields.dateActionTerima[0];
                this.modalJabatanFields.endDate = this.modalJabatanFields.dateActionTerima[1];
            }
            var param = {
                employeeId: this.detailJabatanData.employeeId,
                status: (!this.isEdit ? 0 : this.modalJabatanFields.status),
                startDate: this.modalJabatanFields.startDate,
                endDate: this.modalJabatanFields.endDate,
                jabatan: this.modalJabatanFields.roleCode,
                isEdit: this.isEdit,
                roleCodeValueEdit: this.detailJabatanData.roleCode
            };
            console.log(param);

            try {

                var formdata = new FormData();

                formdata.append("listData", JSON.stringify(param));
                this.isLoadingHistoryJabatan = true;
                var responseData = await axios.post(process.env.VUE_APP_SERVICE_URL + 'employee/savePosition', formdata);
                var responsStatus = false;
                if (responseData.data[0].isDuplicate == 0) {
                    responsStatus = true;
                } else {
                    responsStatus = false;
                }

                this.responseAlert.color = (responsStatus ? 'cyan darken-2' : 'orange');
                this.responseAlert.message = (responsStatus ? "Jabatan berhasil tersimpan" : "Duplikasi data, data sudah ada");
                this.loadingUploadButton = false;
                this.isShowAlertDialogDetail = true;
                this.isLoadingHistoryJabatan = false;
                await this.getHistoryJabatan(param.employeeId);
                this.dialogJabatan = false;
            } catch (error) {
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlertDialogDetail = true;
                this.isLoadingHistoryJabatan = false;
                this.dialogJabatan = false;
            }

        },
        async getHistoryJabatan(employeeId) {
            try {
                this.isLoadingHistoryJabatan = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee/history", { params: { employeeId: employeeId } });
                this.listDataRiwajatJabatan = response.data;
                this.isLoadingHistoryJabatan = false;
            } catch (error) {
                console.log(error.response);
                this.isLoadingHistoryJabatan = false;
            }
        },
        async getSettings() {

            this.$store.dispatch('settings', this.themeColoring);
        },
        reloadData() {

            this.getHistoryJabatan(this.detailJabatanData.employeeId);
        },
        rowClick(row) {

            this.getHistoryJabatan(row.employeeId);
            this.dialogDetail = true;
            this.detailJabatanData = row;
            this.isShowAlertDialogDetail = false;
        },
        rowClickHistoryJabatan(row) {
            this.isShowAlertDialogDetail = false;
            console.log("yes");
            if (row.employeeId) {
                this.isEdit = true;
            } else {
                this.isEdit = false;
            }

            console.log(row);
            var dates = (row.startDate ? [row.startDate, row.endDate] : []);
            this.dialogJabatan = true;
            this.modalJabatanFields = {
                employeeId: row.employeeId,
                roleCode: row.roleCode,
                dateActionTerima: dates,
                status: row.status == 'Aktif' ? true : false,
                roleCodeValueEdit: row.roleCode,
                selectedjabatan: this.modalJabatanFields.selectedjabatan
            };
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
        },
        clear() {
            var remappingParam = {
                employeeIdValue: "",
                nameValue: "",
                jabatanValue: "",
                kodeValue: "",
                levelValue: "",
                searchingParams: []
            };
            this.filter = remappingParam;
        },
        clearJabatan() {
            this.modalJabatanFields = {
                employeeId: "",
                roleCode: "",
                startDate: null,
                endDate: null,
                status: 0,
                modalDateTglTerima: null,
                dateActionTerima: [],
                selectedjabatan: []
            };
        },
        async searching() {
            var remappingParam = {
                employeeIdValue: this.filter.employeeIdValue,
                nameValue: this.filter.nameValue,
                jabatanValue: this.filter.jabatanValue,
                kodeValue: this.filter.kodeValue,
                levelValue: this.filter.levelValue
            };
            this.filter.searchingParams = remappingParam;
            console.log(this.filter.searchingParams);
            await this.getEmployee();
        },
    },
    created() {
        this.getSettings();
        this.getEmployee();
        this.getPosition();
    },
    computed: {
        ...mapGetters(['user', 'settings']),
        dateRangeText() {
            return this.modalJabatanFields.dateActionTerima.join(' ~ ')
        },

    }
}
</script>

<style lang="css" scoped>
.errorColor {
    background-color: #FFCDD2 !important;
}

#table>.v-data-footer .v-icon {
    color: black !important;
}

.table-style>>>tbody tr:hover {
    cursor: pointer;
    background: #0097A7 !important;
    color: white;
}
</style>