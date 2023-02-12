 
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Nadine</h1>
        </div>
        <v-card class="my-5">
            <v-card-title>Filter
                <v-spacer></v-spacer>
                <v-btn v-if="userLocalData.roleLevel == 0" @click="dialogReview = true" text small
                    color="cyan darken-2 mr-2" class="white--text">
                    <v-icon>mdi-upload</v-icon>
                    Upload</v-btn>
                <v-btn v-if="userLocalData.roleLevel == 0" @click="dialogReview = true" text small
                    color="cyan darken-2 mr-2" class="white--text">
                    <v-icon>mdi-file-excel-outline</v-icon>
                    Original File</v-btn>
                <v-divider vertical class="mx-2"></v-divider>
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
                                    <v-text-field outlined dense label="Tgl. Penerimaan" prepend-icon="mdi-calendar"
                                        readonly v-bind="attrs" v-on="on" v-model="dateRangeText"></v-text-field>
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
                            <v-dialog ref="dialogmodalDateTglSurat" :return-value.sync="filter.modalDateTglSurat"
                                persistent width="290px">
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
                                <v-col cols="12" md="6">
                                    <v-text-field dense label="Dari" v-model="filter.dari" outlined clearable
                                        required></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field dense label="Kepada" v-model="filter.kepada" outlined clearable
                                        required></v-text-field>
                                </v-col>

                            </v-row>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field dense label="Isi Ringkasan" v-model="filter.isiRingkasan" outlined
                                        clearable required></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">

                                    <v-text-field dense outlined clearable v-model="filter.sifatSurat"
                                        label="Sifat Surat" required></v-text-field>

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
                <v-alert text dense close-icon="mdi-close-circle-outline" :color="responseAlert.color"
                    v-model="isShowAlert" elevation="2" icon="mdi-information-outline" border="left" dismissible
                    transition="scale-transition">
                    {{ responseAlert.message }}
                </v-alert>
            </v-container>

            <v-data-table multi-sort :headerProps="headerprops" :headers="headers" class="mx-3" :items="listData"
                :search="search" :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''"
                :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
                <template v-slot:item.num="{ index, item }">
                    {{ index+ 1 }}
                </template>
                <template v-slot:item.to="{ item }">
                    <ul>
                        <li v-for="values in splitString(item.to)">
                            {{ values }}</li>
                    </ul>
                </template>

            </v-data-table>
        </v-card>

        <v-dialog v-model="dialogReview" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar color="cyan darken-2" class="white--text">
                    <v-btn icon dark @click="closeModalReview">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Upload and Review</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-container>
                    <v-main>
                        <h3> <v-icon class="mx-3">mdi-file-outline</v-icon> Upload</h3>
                        <form class="my-5">
                            <v-row>
                                <v-col md="4">
                                    <v-file-input label="Browse File" v-model="uploadedValue"
                                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                        @change="handleFilesUpload" outlined chips truncate-length="50" dense
                                        prepend-icon="mdi-paperclip"></v-file-input>
                                    <span class="text--secondary">
                                        <v-icon>mdi-information-outline</v-icon>
                                        Contoh Penamaan File : SDB_2023_01_01.xlsx</span>
                                </v-col>
                                <v-col md="6">
                                    <v-btn :disabled="disabledWhenLoading || loadingUploadButton" color="cyan darken-2"
                                        class="white--text" @click="processUpload" :loading="loadingUploadButton">
                                        <v-icon>mdi-cloud-arrow-up-outline</v-icon> Submit
                                    </v-btn>
                                    <v-btn text :disabled="disabledCancelWhenLoading || loadingUploadButton"
                                        class="mr-4 white--text" color="blue-grey" @click="clearUploadValue">
                                        <v-icon>mdi-cached</v-icon> Clear
                                    </v-btn>
                                </v-col>
                                <v-col md="12" v-if="isShowAlertReview">
                                    <v-alert text dense close-icon="mdi-close-circle-outline"
                                        :color="responseAlertReview.color" elevation="2" icon="mdi-information-outline"
                                        border="left" dismissible transition="scale-transition">
                                        {{ responseAlertReview.message }}
                                    </v-alert>

                                </v-col>

                            </v-row>
                        </form>

                    </v-main>
                    <v-progress-linear v-show="loadingUploadButton" indeterminate
                        color="cyan darken-2"></v-progress-linear>
                    <v-divider></v-divider>
                    <v-card-title>
                        Status
                        <v-spacer></v-spacer>
                        <div class="font-weight-normal">

                            <v-chip class="mx-3" outlined color="indigo" dark> <v-icon
                                    class="mr-1">mdi-file-excel-outline</v-icon>
                                {{ responseSummaryDataReview.totalOriginalSource }} Sources</v-chip>

                            <v-chip class="mx-3" :outlined="uploadStatus.all" color="blue-grey" dark
                                @click="filterUploadedData('all')"> <v-icon class="mr-1">mdi-progress-upload</v-icon>
                                {{ responseSummaryDataReview.totalUploadedData }} Total Data</v-chip>

                            <v-chip class="mx-3" :outlined="uploadStatus.error" color="red" dark
                                @click="filterUploadedData('error')"> <v-icon
                                    class="mr-1">mdi-close-circle-outline</v-icon>
                                {{ responseSummaryDataReview.totalErrors }} Errors</v-chip>

                            <v-chip class="mx-3" :outlined="uploadStatus.success" color="cyan darken-2" dark
                                @click="filterUploadedData('success')"> <v-icon
                                    class="mr-1">mdi-check-circle-outline</v-icon>
                                {{ responseSummaryDataReview.totalSuccess }} Ready to save</v-chip>

                            <v-chip class="mx-3" :outlined="uploadStatus.info" color="blue" dark
                                @click="filterUploadedData('info')"> <v-icon
                                    class="mr-1">mdi-information-outline</v-icon>
                                {{ responseSummaryDataReview.totalUnknown }} Unknown</v-chip>

                        </div>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="searchReview" outlined dense append-icon="mdi-magnify" label="Search"
                            hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table item-key="indexNumber" multi-sort :headerProps="headerprops" :headers="headersReview"
                        :items="!!uploadedValue ? listDataReview : []" :search="searchReview" :loading="isLoadingReview"
                        :loading-text="isLoadingReview ? 'Loading... Please wait' : ''" show-expand
                        :single-expand="singleExpand" :expanded.sync="expanded" class="elevation-1" :footer-props="{
                            showFirstLastPage: true,
                            firstIcon: 'mdi-arrow-collapse-left',
                            lastIcon: 'mdi-arrow-collapse-right',
                            prevIcon: 'mdi-minus',
                            nextIcon: 'mdi-plus'
                        }">
                        <template v-slot:item.num="{ index, item }">
                            {{ index+ 1 }}
                        </template>
                        <template v-slot:item.status="{ index, item }">
                            <span v-if="item.status == 'info'"><v-icon color="blue">mdi-information-outline</v-icon>
                                Unknown</span>
                            <span v-if="item.status == 'error'"><v-icon color="red">mdi-close-circle-outline</v-icon>
                                Error</span>
                            <span v-if="item.status == 'success'"><v-icon
                                    color="cyan darken-2">mdi-check-circle-outline</v-icon> Ready to save</span>
                        </template>
                        <template v-slot:item.data-table-expand="{ item, expand, isExpanded }">
                            <td v-if="item.status == 'error' || item.status == 'info'" class="text-start">
                                <v-btn icon @click="expand(!isExpanded)" class="v-data-table__expand-icon"
                                    :class="{ 'v-data-table__expand-icon--active': isExpanded }">
                                    <v-icon>mdi-chevron-down</v-icon>
                                </v-btn>
                            </td>
                        </template>
                        <template v-slot:expanded-item="{ item, headers }">
                            <td :colspan="headers.length">
                                <p :class="item.status == 'error' ? 'red--text' : 'blue--text'">{{ item.message }}</p>
                            </td>
                        </template>

                        <template v-slot:item.from="{ item }">
                            <p class="my-2">
                                {{ item.from }}
                            </p>
                        </template>

                        <template v-slot:item.to="{ item }">
                            <ul>
                                <li v-for="values in splitString(item.to)">
                                    {{ values }}</li>
                            </ul>
                        </template>

                    </v-data-table>
                </v-container>

            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from 'axios';
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import { mapGetters } from 'vuex';
import moment from 'moment';

var maxlength = 18;
export default {
    // mixins: [validationMixin],
    // validations: {
    //     dari: { required, maxLength: maxLength(maxlength) },
    //     password: { required, maxLength: maxLength(maxlength) },
    // },
    mixins: [validationMixin],
    validations: {
        upload: { required },
    },
    data() {
        return {
            rules: [
                v => !!v || 'File is required',
                v => (v && v.length > 0) || 'File is required',
            ],
            listResponseStatus: [
                {
                    id: "all",
                    name: "All"
                },
                {
                    id: "error",
                    name: "Error"
                },
                {
                    id: "success",
                    name: "Success"
                }
            ],
            listDataReview: [],
            isLoadingReview: false,
            dialogReview: false,
            isAdvanceSearch: false,
            isShowTable: false,
            date: null,
            menuDate: false,
            modalDate: false,
            dialogDetail: false,
            isShowAlert: false,
            isShowAlertReview: false,
            loadingUploadButton: false,
            search: "",
            searchReview: "",
            listData: [],
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
            userDefault: "",
            isReciverShow: false,
            selectedType: "",
            listItemsReciver: [],
            isLoading: false,
            responseAlert: {
                message: "",
                color: ""
            },
            responseAlertReview: {
                message: "",
                color: ""
            },
            responseSummaryDataReview: {
                totalErrors: 0,
                totalSuccess: 0,
                totalUploadedData: 0,
                totalUnknown: 0,
                totalOriginalSource: 0
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
                searchingParams: []
            },
            files: [],
            alertNotready: false,
            search: "",
            listData: [],
            uploadedValue: null,
            dialogUpload: false,
            isShowAlert: false,
            responseAlert: {
                message: "",
                color: ""
            },
            file: '',
            content: [],
            userLocalData: [],
            parsed: false,
            headers: [
                { text: 'No', value: 'num' },
                { text: 'No. Agenda', value: 'agendaNumber' },
                { text: 'No. Surat', value: 'number' },
                { text: 'Tgl. Terima', value: 'receiptDate' },
                { text: 'Tgl. Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'type' },
                { text: 'Dari', value: 'from' },
                // { text: 'Kepada', value: 'toName' },
                { text: 'Kepada', value: 'to', width: '15%' },
                { text: 'Uploader', value: 'unitTo', width: '5%' },
                { text: 'Isi Ringkasan', value: 'note' },
                { text: 'Keterangan', value: 'ket', width: '10%' },
            ],
            headersReview: [
                { text: 'No', value: 'num' },
                { text: 'Agenda', value: 'agendaNumber', width: '5%' },
                { text: 'No Surat', value: 'number', width: '5%' },
                { text: 'Tgl. Terima', value: 'receiptDate', width: '5%' },
                { text: 'Tgl. Surat', value: 'realDate', width: '5%' },
                { text: 'Sifat Surat', value: 'type', width: '10%' },
                { text: 'Dari', value: 'from', width: '15%' },
                { text: 'Kepada', value: 'to', width: '15%' },
                { text: 'Uploader', value: 'unitTo', width: '5%' },
                { text: 'Ket', value: 'desc', width: '5%' },
                { text: 'Isi Ringkasan', value: 'note', width: '25%' },
                { text: 'Status', value: 'status', width: '10%' },
                { text: 'Detail', value: 'data-table-expand' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            },
            expanded: [],
            singleExpand: false,
            uploadStatus: {
                all: true,
                error: true,
                success: true,
                info: true
            },
            isClearFile: true
        }
    },
    methods: {
        async getFiltersData() {
            try {
                if (this.userLocalData) {
                    var responsesParent = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee");
                    var listParent = [];
                    if (responsesParent != undefined) {
                        responsesParent.data.forEach(element => {
                            if (this.userLocalData.employeeId != element.employeeId) {
                                listParent.push(element.employeeId + "-" + element.name);
                            }
                        });
                    }
                    this.listItemsReciver = listParent;
                    this.isLoading = false;
                }
            } catch (error) {
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }

        },
        async getTracking() {
            try {

                this.isLoading = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking", { params: { searchingParams: this.filter.searchingParams } });
                this.listData = !!response ? response.data : [];
                const state = {
                    tracking: !!response ? response.data : [],
                    tempTracking: [],
                    totalOriginalData: 0
                }
                this.$store.dispatch('trackings', state);
                this.isShowTable = true;
                this.isLoading = false;
            } catch (error) {
                console.log(error);
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }
        },
        rowClick(row) {
            this.dialogDetail = true;
            const filteredList = this.allTrackingData.data.filter((e) => e.agendaNumber === row.agendaNumber)
                .map((e) => { return e });

            this.detailDataRow = row;
            this.date = moment(String(row.receiptDate)).format('YYYY-MM-DD');
            this.detailDataList = filteredList;
            // var listData = JSON.parse(localStorage.getItem('userData'));
            this.userDefault = this.userLocalData.name;
        },
        async searching() {

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
                note: this.filter.isiRingkasan
            };
            this.filter.searchingParams = remappingParam;
            await this.getTracking();
        },
        submit() {
            this.$v.$touch()
        },
        async clear() {
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
            this.isAdvanceSearch = value;
        },
        toggle() {
            this.$nextTick(() => {
                if (this.listSelectionType) {
                    this.filter.sifatSurat = []
                } else {
                    this.filter.sifatSurat = this.$store.state.lookup.lookups['type'];
                }
            })
        },
        async processUpload() {
            try {
                var listData = this.mappingMultipleRecipient();
                if (listData.length > 0) {
                    var formdata = new FormData();
                    this.loadingUploadButton = true;
                    formdata.append("listData", JSON.stringify(listData));
                    var ResonseData = await axios.post(process.env.VUE_APP_SERVICE_URL + 'tracking/create', formdata);          // var unknown = data.filter((e) => e.status === 'info').map((e) => {
                    console.log(ResonseData);

                    if (ResonseData.data[0].status != 'success') {
                        this.isShowAlertReview = true;
                        this.responseAlertReview.color = 'error';
                        this.responseAlertReview.message = ResonseData.data.length + " Data sudah ada (Duplikasi)";
                        this.loadingUploadButton = false;
                    } else {
                        this.isShowAlertReview = true;
                        this.responseAlertReview.color = 'cyan darken-2';
                        this.responseAlertReview.message = "Data Nadine Berhasil Tersimpan";
                        this.loadingUploadButton = false;
                        this.isClearFile = false;
                    }

                    // var errorCount = resData.filter((e) => e.status == "error").map((e) => { return e }).length;
                    // console.log(errorCount);

                } else {
                    this.isShowAlertReview = true;
                    this.responseAlertReview.color = 'error';
                    this.responseAlertReview.message = "Maaf, sepertinya tidak ada data yang tersedia untuk disimpan, periksa kembali data anda..";
                }

                // setTimeout(() => {
                //     this.isShowAlertReview = false;
                this.isLoadingReview = false;
                // }, 5000);
            } catch (error) {
                this.isLoadingReview = false;
                console.log(error);
            }
        },

        mappingMultipleRecipient() {
            var data = this.$store.state.trackings['trackings'].tempTracking;
            var createdBy = this.userLocalData.employeeId;
            var createdDate = new moment(new Date).locale('id');

            var listData = data.filter((e) => e.status != "error").map((e) => {
                return {
                    trackingid: (String(createdBy + e.unitTo) + Math.random().toString(36).slice(3)).toLowerCase(),
                    unknownId: (String(createdBy) + Math.random().toString(36).slice(2)).toLowerCase(),
                    agendaNumber: e.agendaNumber, receiptDate: moment(e.receiptDate, 'DD-MM-YYYY').format('YYYY-MM-DD'),
                    realDate: moment(e.realDate, 'DD-MM-YYYY').format('YYYY-MM-DD'), type: e.type,
                    from: e.from,
                    fromPrefix: (e.from.toLowerCase()).replaceAll(' ', ''),
                    to: e.to,
                    unitTo: (e.status === 'info' ? null : e.unitTo),
                    isUnknown: (e.status === 'info' ? 'Y' : 'N'), description: e.note,
                    number: e.number,
                    note: e.note,
                    createdBy: createdBy,
                    createdDate: createdDate,
                    dataType: "Upload",
                    ket: e.desc
                }
            });
            // var listMultipleData = [];
            // var listSingleData = [];
            // var maergeData = [];

            // listData.forEach((e, index) => {
            //     index = index + 1;
            //     if (e.to.code.length > 0) {
            //         e.to.code.forEach(element => {
            //             var newData = {
            //                 agendaNumber: e.agendaNumber,
            //                 receiptDate: moment(e.receiptDate, 'DD-MM-YYYY').format('YYYY-MM-DD'),
            //                 realDate: moment(e.realDate, 'DD-MM-YYYY').format('YYYY-MM-DD'),
            //                 type: e.type,
            //                 from: e.from, to: element.code, isUnknown: (e.status === 'info' ? 'Y' : 'N'), description: e.description,
            //                 number: e.number,
            //                 ket: e.desc,
            //                 note: e.note,
            //                 createdBy: createdBy,
            //                 createdDate: createdDate,
            //                 dataType: "Upload",
            //                 unitTo: e.unitTo,
            //                 sequence: index // auto increment

            //             }
            //             listMultipleData.push(newData);
            //         });
            //     } else {
            //         var newData = {
            //             agendaNumber: e.agendaNumber,
            //             receiptDate: moment(e.receiptDate, 'DD-MM-YYYY').format('YYYY-MM-DD'),
            //             realDate: moment(e.realDate, 'DD-MM-YYYY').format('YYYY-MM-DD'),
            //             type: e.type,
            //             from: e.from, to: e.to.name, isUnknown: (e.status === 'info' ? 'Y' : 'N'), description: e.description,
            //             number: e.number,
            //             ket: e.desc,
            //             createdBy: createdBy,
            //             createdDate: createdDate,
            //             dataType: "Upload",
            //             note: e.note,
            //             unitTo: e.unitTo,
            //             sequence: 1 // auto increment
            //         }
            //         listSingleData.push(newData);
            //     }
            // });

            // maergeData = listMultipleData.concat(listSingleData);
            return listData;
        },
        async clearUploadValue() {
            this.expanded = [];
            this.isShowAlertReview = false;
            this.responseSummaryDataReview.totalErrors = 0;
            this.responseSummaryDataReview.totalSuccess = 0;
            this.responseSummaryDataReview.totalUploadedData = 0;
            this.responseSummaryDataReview.totalUnknown = 0;
            this.responseSummaryDataReview.totalOriginalSource = 0;

            try {

                this.isLoading = true;
                if (this.isClearFile && this.uploadedValue != null) {
                    await axios.get(process.env.VUE_APP_SERVICE_URL + "removefile", { params: { fileName: this.uploadedValue.name } }).then((data) => {
                        if (!!data) {
                            console.log('masuk sukses');
                        }
                    });
                }
                this.uploadedValue = null;

            } catch (error) {
                this.uploadedValue = null;
                console.log(error);
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }

        },
        async handleFilesUpload() {
            try {
                this.isClearFile = true;
                var file = event.target.files[0].name;
                var prefixFile = this.$store.state.lookup.lookups['filePrefix'];
                console.log(file.split('_'));
                if (file.split('_').length > 1) {

                    var datas = prefixFile.filter(e => e.code == file.split('_')[0]).map(el => { return el });
                    console.log(datas);
                } else {
                    this.isShowAlertReview = true;
                    this.responseAlertReview.color = 'red';
                    this.responseAlertReview.message = 'Periksa kembali format nama file anda. Contoh : SDB_2023_01_01.xlsx';
                    this.uploadedValue = null;
                    return null;
                }

                this.userLocalData;
                let formData = new FormData();
                console.log(this.uploadedValue.name);
                formData.append('TrackingFileUpload', this.uploadedValue);
                formData.append('unitTo', file.split('_')[0]);
                this.dialogReview = true;
                this.isLoadingReview = true;
                this.listDataReview = [];
                var listData = await axios.post(process.env.VUE_APP_SERVICE_URL + 'upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                );

                this.listDataReview = !!listData ? listData.data.responseData : [];
                const state = {
                    tracking: [],
                    tempTracking: !!listData ? listData.data.responseData : [],
                    totalOriginalData: listData.data.totalOriginalData
                }
                this.$store.dispatch('trackings', state);
                this.isLoadingReview = false;
                this.listDataReview.forEach((item, i) => {
                    item.indexNumber = i + 1;
                });
            } catch (error) {
                console.log(error.response.status);
                this.isShowAlertReview = true;
                this.responseAlertReview.color = 'red';
                this.responseAlertReview.message = error.response.status == 404 ? error.response.data.message : "You have error, please refresh this page. Detail : " + error.response;
                this.isLoadingReview = false;
            }
            this.summaryUploadReview();
        },
        filterUploadedData(status) {
            this.uploadStatus.all = true;
            this.uploadStatus.error = true;
            this.uploadStatus.success = true;
            this.uploadStatus.info = true;

            if (status == 'all') {
                this.uploadStatus.all = false;
            } else if (status == 'error') {
                this.uploadStatus.error = false;
            } else if (status == 'info') {
                this.uploadStatus.info = false;
            } else if (status == 'success') {
                this.uploadStatus.success = false;
            } else if (status == 'info') {
                this.uploadStatus.error = false;
            }


            var data = this.$store.state.trackings['trackings'].tempTracking;
            var filteredList = [];
            filteredList = status == 'all' ? data : data.filter((e) => e.status === status).map((e) => {
                return {
                    agendaNumber: e.agendaNumber, receiptDate: e.receiptDate, realDate: e.realDate, type: e.type,
                    from: e.from, to: e.to, unitTo: e.unitTo, status: e.status, note: e.note, message: e.message,
                    number: e.number, desc: e.desc
                }
            });
            this.listDataReview = filteredList;
            this.listDataReview.forEach((item, i) => {
                item.indexNumber = i + 1;
            });
        },
        itemRowBackground(item) {
            if (item === 'error') {
                return 'errorColor';
            }
        },
        summaryUploadReview() {
            var data = this.$store.state.trackings['trackings'].tempTracking;
            var total = this.$store.state.trackings['trackings'].totalOriginalData;
            this.responseSummaryDataReview.totalUploadedData = data.length;
            this.responseSummaryDataReview.totalErrors = data.filter((e) => e.status === 'error').map((e) => { return { e } }).length;
            this.responseSummaryDataReview.totalSuccess = data.filter((e) => e.status === 'success').map((e) => { return { e } }).length;
            this.responseSummaryDataReview.totalUnknown = data.filter((e) => e.status === 'info').map((e) => { return { e } }).length;
            this.responseSummaryDataReview.totalOriginalSource = total;
        },
        splitString(item) {
            var listData = !!item ? item.split(";") : "";
            return listData;
        },
        closeModalReview() {
            this.dialogReview = false;
            this.getTracking();
        },
    },
    created() {
        this.getTracking();
        var userData = JSON.parse(localStorage.getItem('userData'));
        this.userLocalData = userData.user;
        // this.getFiltersData();
    },
    computed: {
        ...mapGetters(['inboxs', 'settings', 'lookups', 'tracking']),
        latterType() {
            return this.$store.state.lookup.lookups['type'];
        },
        listSelectionType() {
            return this.filter.sifatSurat.length === this.$store.state.lookup.lookups['type'].length
        },
        icon() {
            if (this.listSelectionType) return 'mdi-close-box'
            if (this.likesSomeFruit) return 'mdi-minus-box'
            return 'mdi-checkbox-blank-outline'
        },
        likesSomeFruit() {
            return this.filter.sifatSurat.length > 0 && !this.listSelectionType
        },
        uploadErrors() {
            const errors = []
            if (!this.$v.upload.$dirty) return errors
            !this.$v.upload.required && errors.push('This field is required.')
            return errors
        },
        isValid() {
            if (this.upload) {
                return false;
            } else {
                return true;
            }
        },
        disabledWhenLoading() {
            return this.isShowAlertReview ? true : (!this.isLoadingReview && !!this.uploadedValue ? false : true);
        },
        disabledCancelWhenLoading() {
            return this.isShowAlertReview ? false : (this.isLoadingReview && !!this.uploadedValue ? true : false);
        },
        dateRangeText() {
            return this.filter.dateActionTerima.join(' ~ ')
        },
        dateRangeSuratText() {
            return this.filter.dateActionSurat.join(' ~ ')
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