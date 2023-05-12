 
<template>
    <v-container>
        <v-overlay v-if="isOverlayLoading" class="align-center justify-center">
            <v-progress-circular color="white" indeterminate size="64" width="7"></v-progress-circular>
        </v-overlay>
        <div>
            <h1 class="font-weight-medium">Duplication Box</h1>
        </div>

        <v-card class="my-5">
            <v-card-title>Filter
                <v-spacer></v-spacer>
                <v-btn class="mr-4 white--text" color="cyan darken-2" small dark @click="advanceSearch(false)"
                    v-if="isAdvanceSearch">
                    <v-icon>mdi-arrow-up-thin</v-icon>
                    Simple Search
                </v-btn>
                <v-btn v-else class="white--text" color="blue-grey" text small dark @click="advanceSearch(true)">
                    <v-icon>mdi-arrow-down-thin</v-icon>
                    Advance Search
                </v-btn>
            </v-card-title>

            <v-form>
                <v-container>
                    <v-row>
                        <v-col cols="12" md="3">
                            <v-text-field dense outlined v-model="filter.noAgenda" label="Nomor Agenda"
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-text-field dense label="Nomor Surat" v-model="filter.noSurat" outlined clearable
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-dialog ref="dialogmodalDateTglTerima" :return-value.sync="filter.modalDateTglTerima"
                                persistent width="290px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field outlined dense label="Tgl. Terima" prepend-icon="mdi-calendar" readonly
                                        v-bind="attrs" v-on="on" v-model="dateRangeText"></v-text-field>
                                </template>
                                <v-date-picker dense v-model="filter.dateActionTerima" range type="date" scrollable>
                                    <v-spacer></v-spacer>

                                    <v-btn text color="primary"
                                        @click="$refs.dialogmodalDateTglTerima.save(filter.modalDateTglTerima)">
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>
                        <v-col cols="12" md="3">
                            <v-dialog ref="dialogmodalDateTglSurat" :return-value.sync="filter.modalDateTglSurat" persistent
                                width="290px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field outlined dense label="Tgl. Surat" prepend-icon="mdi-calendar" readonly
                                        v-bind="attrs" v-on="on" v-model="dateRangeSuratText"></v-text-field>
                                </template>
                                <v-date-picker dense v-model="filter.dateActionSurat" range type="date" scrollable>
                                    <v-spacer></v-spacer>

                                    <v-btn text color="primary"
                                        @click="$refs.dialogmodalDateTglSurat.save(filter.modalDateTglSurat)">
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>
                        <!-- show if is advanced search -->
                        <v-container v-if="isAdvanceSearch">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field dense label="Dari" v-model="filter.dari" outlined clearable
                                        required></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field dense label="Kepada" v-model="filter.kepada" outlined clearable
                                        required></v-text-field>
                                </v-col>

                                <v-col md="4">
                                    <v-select v-model="filter.unknownModelData" dense outlined :items="unknownData"
                                        item-text="name" item-value="id" label="Unknown?"></v-select>
                                </v-col>

                            </v-row>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field dense label="Isi Ringkasan" v-model="filter.isiRingkasan" outlined
                                        clearable required></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">

                                    <v-text-field dense outlined clearable v-model="filter.sifatSurat" label="Sifat Surat"
                                        required></v-text-field>

                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field dense outlined clearable v-model="filter.ket" label="Keterangan"
                                        required></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
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

            <v-data-table item-key="indexNumber" multi-sort :headerProps="headerprops" :headers="headers"
                class="mx-3 table-style" :items="listUnknownData != null ? listUnknownData : []" :loading="isLoadingUnknown"
                :loading-text="isLoadingUnknown ? 'Loading... Please wait' : ''" @click:row="takeItAction" :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
                <template v-slot:item.num="{ item, index }">
                    {{ index + 1 }}
                </template>

                <template v-slot:item.unitAssignedFrom="{ item, index }">

                    <v-chip v-if="item.unitAssignedFrom" color="cyan darken-2" dark
                        :disabled="item.isExists ? true : false">
                        {{ item.unitAssignedFrom }} <v-icon class="mx-1">mdi-check-circle-outline</v-icon>
                    </v-chip>
                    <v-chip v-else color="orange" dark>
                        Unknown <v-icon class="mx-1">mdi-alert-outline</v-icon>
                    </v-chip>
                </template>

            </v-data-table>


        </v-card>

        <v-row justify="center">
            <v-dialog v-model="dialogUnknown" max-width="700px">
                <v-card>
                    <v-card-title class="dragable">
                        <span class="text-h5">Unit {{ userDefault.roleCode }}</span>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field class="mb-3" v-model="searchUnknown" append-icon="mdi-magnify" label="Search"
                            single-line hide-details></v-text-field>
                        <v-data-table ref="unknownTable" :items-per-page="5" :loading="isLoadingUnknown"
                            :loading-text="isLoadingUnknown ? 'Loading... Please wait' : ''"
                            :footer-props="{ 'items-per-page-options': [5, 10, 50, 100, -1] }" :headers="headersUnknown"
                            :items="listUnitUnknown" :search="searchUnknown" :item-class="itemRowBackground">
                            <template v-slot:item.num="{ index }">
                                {{ index + 1 }}
                            </template>

                            <template v-slot:item.takeIt="{ item }">
                                <v-btn v-if="item.unitAssignedFrom" :disabled="disabledUnknownButton"
                                    @click="moveToInbox(item, false)" small color="orange" class="white--text">
                                    Batalkan <v-icon class="mx-1">mdi-remove-outline</v-icon>
                                </v-btn>
                                <v-btn v-else :disabled="disabledUnknownButton" @click="moveToInbox(item, true)" small
                                    color="cyan darken-2" class="white--text mr-2">
                                    OK <v-icon class="mx-1">mdi-check-circle-outline</v-icon>
                                </v-btn>

                            </template>

                        </v-data-table>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="cyan darken-2" text @click="dialogUnknown = false">
                            OK
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </v-container>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
import moment from 'moment';
export default {
    data() {
        return {
            isOverlayLoading: false,
            isAdvanceSearch: false,
            isShowTable: false,
            date: null,
            menuDate: false,
            modalDate: false,
            dialogDetail: false,
            isShowAlert: false,
            search: "",
            listUnknownData: [],
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
            listType: ['Arsipkan', 'Teruskan', 'Disposisi'],
            userDefault: {},
            isReciverShow: false,
            selectedType: "",
            listItemsReciver: [],
            isLoading: false,
            isLoadingUnknown: false,
            responseAlert: {
                message: "",
                color: ""
            },
            filter: {
                range: true,
                sifatSurat: "",
                noAgenda: "",
                noSurat: "",
                dari: "",
                kepada: "",
                ket: "",
                isiRingkasan: "",
                dateActionTerima: [],
                dateActionSurat: [],
                tglTerimaStart: "",
                tglTerimaEnd: "",
                tglSuratStart: "",
                tglSuratEnd: "",
                modalDateTglTerima: null,
                modalDateTglSurat: null,
                searchingParams: [],
                unknownModelData: ""
            },
            headers: [
                { text: 'No', value: 'num' },
                { text: 'No.Agenda', value: 'nomorAgenda' },
                { text: 'Tgl.Penerimaan', value: 'tglPenerimaanDisplayText' },
                { text: 'No.Surat', value: 'nomorSurat' },
                { text: 'Tgl.Surat', value: 'tglSuratDisplayText' },
                { text: 'Sifat Surat', value: 'sifatSurat' },
                { text: 'Isi Ringkasan', value: 'isiRingkasan' },
                { text: 'Dari', value: 'dari' },
                { text: 'Kepada', value: 'kepada' },
                { text: 'Assigned To', value: 'unitAssignedFrom' }

            ],
            searchUnknown: "",
            listUnitUnknown: [],
            pagination: {
                rowsPerPage: 5
            },
            headersUnknown: [
                { text: 'No', value: 'num', with: '10%' },
                { text: 'Unit', value: 'positionName', with: '60%' },
                { text: 'Pindahkan', value: 'takeIt', with: '20%' }

            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            },
            dialogUnknown: false,
            detailUnknownData: [],
            disabledUnknownButton: false,
            loadingUploadButton: false,
            unknownData: [
                {
                    id: "",
                    name: "Semua"
                },
                {
                    id: "Y",
                    name: "Ya"
                },
                {
                    id: "N",
                    name: "Bukan"
                }
            ],
        }
    },
    methods: {
        async getUnitParent() {
            try {
                this.isLoadingUnknown = true;
                if (this.userDefault) {
                    var params = {
                        params: {
                            roleCode: this.userDefault.roleCode,
                            isAdmin: this.userDefault.roleLevel,
                            nomorSurat: this.detailUnknownData.nomorSurat,
                            unknownId: this.detailUnknownData.unknownId,
                            unitAssignedFrom: this.detailUnknownData.unitAssignedFrom ?? ""
                        }
                    };
                    var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "unknown/parent", params);
                    this.listUnitUnknown = !!response ? response.data : [];

                    this.isLoadingUnknown = false;
                }

            } catch (error) {
                this.isLoadingUnknown = false;
            }
        },
        async getUnknown() {
            try {
                this.isOverlayLoading = true;
                this.isLoadingUnknown = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "unknown", { params: { searchingParams: this.filter.searchingParams } });
                this.listUnknownData = !!response ? response.data : [];
                this.listUnknownData.forEach((item, i) => {
                    item.indexNumber = i + 1;
                });
                this.isLoadingUnknown = false;
                this.isOverlayLoading = false;
            } catch (error) {
                this.isLoadingUnknown = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
                this.isOverlayLoading = false;
            }
        },
        async getSettings() {
            var listData = JSON.parse(localStorage.getItem('userData'));
            this.userDefault = listData.user;
        },
        rowClick(row) {
            const filteredList = this.$store.state.inboxs['inboxs'].data.filter((e) => e.agendaNumber === row.agendaNumber)
                .map((e) => { return e });
            this.detailDataRow = row;
            this.date = moment(String(row.receiptDate)).format('YYYY-MM-DD');
            this.detailDataList = filteredList;
            this.dialogDetail = true;
            this.isShowAlert = false;
        },
        async searching() {
            this.isShowTable = true;
            var dateActionTerimaStart = "";
            var dateActionTerimaEnd = "";

            var dateActionSuratStart = "";
            var dateActionSuratEnd = "";

            if (this.filter.dateActionTerima.length == 1) {
                dateActionTerimaStart = this.filter.dateActionTerima[0];
            }
            if (this.filter.dateActionTerima.length > 1) {
                dateActionTerimaStart = this.filter.dateActionTerima[0];
                dateActionTerimaEnd = this.filter.dateActionTerima[1];
            }

            if (this.filter.dateActionSurat.length == 1) {
                dateActionSuratStart = this.filter.dateActionSurat[0];
            }
            if (this.filter.dateActionSurat.length > 1) {
                dateActionSuratStart = this.filter.dateActionSurat[0];
                dateActionSuratEnd = this.filter.dateActionSurat[1];
            }
            var remappingParam = {
                employeeId: this.userDefault.employeeId,
                isAdvancedSearch: this.isAdvanceSearch,
                type: this.filter.sifatSurat,
                number: this.filter.noSurat,
                agendaNumber: this.filter.noAgenda,
                from: this.filter.dari,
                to: this.filter.kepada,
                ket: this.filter.ket,
                dateActionTerimaStart: dateActionTerimaStart,
                dateActionTerimaEnd: dateActionTerimaEnd,
                dateActionSuratStart: dateActionSuratStart,
                dateActionSuratEnd: dateActionSuratEnd,
                note: this.filter.isiRingkasan,
                isUnknown: this.filter.unknownModelData,
                unitTo: this.userDefault.roleCode
            }
            this.filter.searchingParams = remappingParam;
            this.getUnknown();
        },
        async submit() {
            try {
                var listData = {
                    trackingId: row.trackingid,
                    from: this.listLocalUserData.roleCode,
                    to: item.code,
                    note: this.description,
                    createdBy: this.listLocalUserData.employeeId,
                    createdDate: new moment(this.dateAction).locale('id')
                }
                var formdata = new FormData();
                this.loadingUploadButton = true;
                formdata.append("listData", JSON.stringify(listData));
                await axios.post(process.env.VUE_APP_SERVICE_URL + 'inbox/create', formdata);            // var unknown = data.filter((e) => e.status === 'info').map((e) => {
                this.dialogDetail = false;
                this.responseAlert.color = 'cyan darken-2';
                this.responseAlert.message = "Data berhasil tersimpan dan masuk ke Outbox";
                this.loadingUploadButton = false;
                this.isShowAlert = true;
                await this.getSettings(this.detailDataRow.agendaNumber);
                this.dialogDetail = false;
                this.getInbox();
                this.disabledModalButtonSave = false;

            } catch (error) {
                console.log(error);
            }
        },
        clear() {
            this.isShowTable = false;
            var remappingParam = {
                sifatSurat: "",
                noAgenda: "",
                noSurat: "",
                dari: "",
                kepada: "",
                ket: "",
                isiRingkasan: "",
                dateActionTerima: [],
                dateActionSurat: [],
                tglTerimaStart: "",
                tglTerimaEnd: "",
                tglSuratStart: "",
                tglSuratEnd: "",
                modalDateTglTerima: null,
                modalDateTglSurat: null,
            };
            this.filter = remappingParam;

        },
        selectedTypeEvnt() {
            if (this.selectedType != 'Arsipkan') {
                this.isReciverShow = true;
            } else {
                this.isReciverShow = false;
            }
        },
        advanceSearch(value) {
            console.log(value);
            this.isAdvanceSearch = value;
        },
        toggle() {
            this.$nextTick(() => {
                if (this.likesAllFruit) {
                    this.filter.sifatSurat = []
                } else {
                    this.filter.sifatSurat = this.$store.state.lookup.lookups['type'];
                }
            })
        },
        toggleColor(isSelected, select, e) {
            select(!isSelected)
        },
        async takeItAction(row) {
            if (row.isExists) {
                this.responseAlert.color = 'orange darken-2';
                this.responseAlert.message = "Data sudah digunakan(Dalam proses), tidak dapat dibatalkan";
                this.loadingUploadButton = false;
                this.isShowAlert = true;
            } else {
                this.isOverlayLoading = true;
                this.detailUnknownData = row;
                await this.getUnitParent();
                this.dialogUnknown = true;
                this.searchUnknown = row.unitAssignedFrom;
                this.isOverlayLoading = false;
            }

        },
        async moveToInbox(item, isCreate) {
            this.isOverlayLoading = true;
            var row = this.detailUnknownData;
            var params = [
                {
                    unknownId: row.unknownId,
                    trackingId: row.trackingId,
                    assignedFromEmployeeId: this.userDefault.employeeId,
                    assignedToEmployeeId: item.employeeId,
                    isCreate: isCreate
                }
            ];
            try {
                if (params) {
                    var formdata = new FormData();
                    this.disabledUnknownButton = true;
                    formdata.append("listData", JSON.stringify(params));
                    await axios.post(process.env.VUE_APP_SERVICE_URL + 'unknown/create', formdata);
                    this.isShowAlert = true;
                    this.responseAlert.color = 'cyan darken-2';
                    this.responseAlert.message = isCreate ? "Data Berhasil Dipindahkan ke Unit " + item.code : "Data Berhasil Dikembalikan ke Unknown";
                    this.dialogUnknown = false;
                } else {
                    this.isShowAlert = true;
                    this.responseAlert.color = 'error';
                    this.responseAlert.message = "Maaf, sepertinya tidak ada data yang tersedia untuk disimpan, periksa kembali data anda..";
                }
                this.isLoading = false;
                this.dialogUnknown = false;
                this.disabledUnknownButton = false;
                await this.getUnknown();
                this.isOverlayLoading = false;
            } catch (error) {
                this.isShowAlert = true;
                this.isLoading = false;
                console.log(error);
                this.dialogUnknown = false;
                this.disabledUnknownButton = false;
                this.responseAlert.color = 'error';
                this.responseAlert.message = error.response.data.message;
                this.isOverlayLoading = false;
            }
        },
        itemRowBackground(item) {
            return item.level == '2' || item.level == '3' ? 'font-weight-medium cyan lighten-5' : '';
        }
    },
    async created() {
        this.isOverlayLoading = true;
        await this.getSettings();
        await this.searching();
        await this.getUnitParent();
        this.isOverlayLoading = false;
    },
    computed: {
        ...mapGetters(['inboxs', 'settings', 'lookups', 'unknowns']),
        latterType() {
            return this.$store.state.lookup.lookups['type'];
        },
        likesAllFruit() {
            return this.filter.sifatSurat.length === this.$store.state.lookup.lookups['type'].length
        },
        icon() {
            if (this.likesAllFruit) return 'mdi-close-box'
            if (this.likesSomeFruit) return 'mdi-minus-box'
            return 'mdi-checkbox-blank-outline'
        },
        likesSomeFruit() {
            return this.filter.sifatSurat.length > 0 && !this.likesAllFruit
        },
        dateRangeText() {
            return this.filter.dateActionTerima.join(' ~ ')
        },
        dateRangeSuratText() {
            return this.filter.dateActionSurat.join(' ~ ')
        },
    },
    mounted() {
        this.activateMultipleDraggableDialogs();
    },
}
</script> 

<style lang="css" scoped>
.table-style>>>tbody tr:hover {
    cursor: pointer;
    background: #0097A7 !important;
    color: white;
}

.activeParent {
    cursor: pointer;
    background: #0097A7 !important;
    color: white;
}

.dragable {
    cursor: all-scroll !important;
}
</style>