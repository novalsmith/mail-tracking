 
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Nadine</h1>
        </div>
        <v-card class="my-5">
            <v-card-title>Filter
                <v-spacer></v-spacer>
                <v-btn @click="dialogReview = true" text small color="cyan darken-2 mr-2" class="white--text">
                    <v-icon>mdi-upload</v-icon>
                    Upload</v-btn>
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

                        <v-col cols="12" md="4">
                            <v-select clearable :items="latterType" item-text="name" item-value="code"
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
                            </v-select>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field dense outlined clearable v-model="filter.noAgenda" label="Nomor Agenda"
                                required></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field dense label="Nomor Surat" v-model="filter.noSurat" outlined clearable
                                required></v-text-field>
                        </v-col>
                        <!-- show if is advanced search -->
                        <v-container v-if="isAdvanceSearch">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field dense label="Dari" v-model="filter.dari" outlined clearable
                                        required></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-combobox :items="listItemsReciver" v-model="filter.kepada" dense outlined
                                        label="Kepada" multiple chips></v-combobox>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-combobox :items="listItemsReciver" v-model="filter.keterangan" dense outlined
                                        label="Keterangan" multiple chips></v-combobox>
                                </v-col>

                                <v-col cols="12" md="3">
                                    <v-text-field dense outlined clearable v-model="filter.tglTerimaStart"
                                        label="Tgl. Penerimaan - Start" required></v-text-field>
                                </v-col>

                                <v-col cols="12" md="3">
                                    <v-text-field dense outlined clearable v-model="filter.tglTerimaEnd"
                                        label="Tgl. Penerimaan - End" required></v-text-field>
                                </v-col>

                                <v-col cols="12" md="3">
                                    <v-text-field dense outlined clearable v-model="filter.tglSuratStart"
                                        label="Tgl. Surat - Start"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-text-field dense outlined clearable v-model="filter.tglSuratEnd"
                                        label="Tgl. Surat - End"></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
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
                                </v-col>
                                <v-col md="6">
                                    <v-btn :disabled="disabledWhenLoading || loadingUploadButton" color="cyan darken-2"
                                        class="white--text" @click="processUpload" :loading="loadingUploadButton">
                                        <v-icon>mdi-cloud-arrow-up-outline</v-icon> Submit
                                    </v-btn>
                                    <v-btn text class="mr-4 white--text" color="blue-grey" @click="clearUploadValue">
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
                            <v-chip small class="my-2">
                                {{ item.from }}
                            </v-chip>
                        </template>

                        <template v-slot:item.to="{ item }">
                            <div v-if="item.to.name != ''">
                                <v-chip small v-for="values in splitString(item.to.name)" class="my-2">
                                    {{ values }}
                                </v-chip>
                            </div>

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
                totalUnknown: 0
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
                { text: 'Agenda', value: 'agendaNumber' },
                { text: 'Terima', value: 'receiptDate' },
                { text: 'Tanggal Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'type' },
                { text: 'Dari', value: 'from' },
                { text: 'Kepada', value: 'toName' },
                { text: 'Isi Ringkasan', value: 'note' },
                { text: 'Ket', value: 'type', width: '10%' },
            ],
            headersReview: [
                { text: 'No', value: 'num' },
                { text: 'Agenda', value: 'agendaNumber', width: '10%' },
                { text: 'No Surat', value: 'number', width: '10%' },
                { text: 'Terima', value: 'receiptDate', width: '10%' },
                { text: 'Tanggal Surat', value: 'realDate', width: '10%' },
                { text: 'Sifat Surat', value: 'type', width: '10%' },
                { text: 'Dari', value: 'from', width: '10%' },
                { text: 'Kepada', value: 'to', width: '20%' },
                { text: 'Ket', value: 'desc', width: '10%' },
                { text: 'Isi Ringkasan', value: 'note', width: '10%' },
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
            }
        }
    },
    methods: {
        submit() { },
        async getEmployeeParentChild() {
            try {
                if (this.userLocalData) {
                    var responsesParent = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee/" + this.userLocalData.roleCode);
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
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking");
                this.listData = response.data;
                const state = {
                    tracking: response.data,
                    tempTracking: []
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
        searching() {
            // this.isShowTable = true;
            var mappArraySifatSurat = [];
            this.filter.sifatSurat.forEach(element => {
                mappArraySifatSurat.push(element);
            });
            var mappArrayKepada = [];
            this.filter.kepada.forEach(element => {
                mappArrayKepada.push(element);
            });

            var mappArrayKet = [];
            this.filter.keterangan.forEach(element => {
                mappArrayKet.push(element);
            });
            var remappingParam = {
                isAdvancedSearch: this.isAdvanceSearch,
                sifatSurat: mappArraySifatSurat,
                noSurat: this.filter.noSurat,
                noAgenda: this.filter.noAgenda,
                dari: this.filter.dari,
                kepada: mappArrayKepada,
                keterangan: mappArrayKet,
                tglTerimaStart: this.tglTerimaStart,
                tglTerimaEnd: this.tglTerimaEnd,
                tglSuratStart: this.tglSuratStart,
                tglSuratEnd: this.tglSuratEnd
            };
            this.getTracking();
        },
        submit() {
            this.$v.$touch()
        },
        clear() {
            // this.isShowTable = false;
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
                var formdata = new FormData();
                this.loadingUploadButton = true;
                formdata.append("listData", JSON.stringify(listData));
                var resultData = await axios.post(process.env.VUE_APP_SERVICE_URL + 'tracking/create', formdata);            // var unknown = data.filter((e) => e.status === 'info').map((e) => {
                this.responseAlertReview.color = 'cyan darken-2';
                this.responseAlertReview.message = "Data Nadine Berhasil Tersimpan";
                this.loadingUploadButton = false;
                this.isShowAlertReview = true;
                setTimeout(() => {
                    this.isShowAlertReview = false;
                }, 2000);
            } catch (error) {
                console.log(error);
            }
        },

        mappingMultipleRecipient() {
            var data = this.$store.state.trackings['trackings'].tempTracking;
            var listData = data.filter((e) => e.status != "error").map((e) => {
                return {
                    agendaNumber: e.agendaNumber, receiptDate: e.receiptDate, realDate: e.realDate, type: e.type,
                    from: e.from, to: e.to, isUnknown: (e.status === 'info' ? 'Y' : 'N'), description: e.note,
                    number: e.number,
                    note: "sample",
                    status: e.status,
                    note: e.note
                }
            });
            var listMultipleData = [];
            var listSingleData = [];
            var maergeData = [];
            var createdBy = this.userLocalData.employeeId;
            var createdDate = new moment(new Date).locale('id');

            listData.forEach((e, index) => {
                index = index + 1;
                if (e.to.code.length > 0) {
                    e.to.code.forEach(element => {
                        var newData = {
                            agendaNumber: e.agendaNumber, receiptDate: e.receiptDate, realDate: e.realDate, type: e.type,
                            from: e.from, to: element.code, isUnknown: (e.status === 'info' ? 'Y' : 'N'), description: e.description,
                            number: e.number,
                            note: e.note,
                            createdBy: createdBy,
                            createdDate: createdDate,
                            dataType: "Upload",
                            sequence: index // auto increment

                        }
                        listMultipleData.push(newData);
                    });
                } else {
                    var newData = {
                        agendaNumber: e.agendaNumber, receiptDate: e.receiptDate, realDate: e.realDate, type: e.type,
                        from: e.from, to: e.to.name, isUnknown: (e.status === 'info' ? 'Y' : 'N'), description: e.description,
                        number: e.number,
                        note: e.note,
                        createdBy: createdBy,
                        createdDate: createdDate,
                        dataType: "Upload",
                        sequence: 1 // auto increment
                    }
                    listSingleData.push(newData);
                }
            });

            maergeData = listMultipleData.concat(listSingleData);
            return maergeData;
        },
        clearUploadValue() {
            this.expanded = [];
            this.uploadedValue = null;
            this.isShowAlertReview = false;
            this.responseSummaryDataReview.totalErrors = 0;
            this.responseSummaryDataReview.totalSuccess = 0;
            this.responseSummaryDataReview.totalUploadedData = 0;
            this.responseSummaryDataReview.totalUnknown = 0;
        },
        async handleFilesUpload() {
            try {
                var file = event.target.files[0].name;
                let formData = new FormData();
                formData.append('TrackingFileUpload', this.uploadedValue);
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

                this.listDataReview = !!listData ? listData.data : [];
                const state = {
                    tracking: [],
                    tempTracking: !!listData ? listData.data : []
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
                    agendaNumber: e.agendaNumber, receiptDate: e.receiptDate, realDate: e.realDate, desc: e.type,
                    from: e.from, to: e.to, status: e.status, note: e.note
                }
            });
            this.listDataReview = filteredList;
        },
        itemRowBackground(item) {
            if (item === 'error') {
                return 'errorColor';
            }
        },
        summaryUploadReview() {
            var data = this.$store.state.trackings['trackings'].tempTracking;
            this.responseSummaryDataReview.totalUploadedData = data.length;
            this.responseSummaryDataReview.totalErrors = data.filter((e) => e.status === 'error').map((e) => { return { e } }).length;
            this.responseSummaryDataReview.totalSuccess = data.filter((e) => e.status === 'success').map((e) => { return { e } }).length;
            this.responseSummaryDataReview.totalUnknown = data.filter((e) => e.status === 'info').map((e) => { return { e } }).length;
        },
        splitString(item) {
            var listData = !!item ? item.split(";") : "";
            return listData;
        },
        closeModalReview() {
            this.dialogReview = false;
            this.getTracking();
        }
    },
    created() {
        this.getTracking();
        var userData = JSON.parse(localStorage.getItem('userData'));
        this.userLocalData = userData.user;
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
        }

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

.errorColor {
    background-color: #FFCDD2 !important;
}

#table>.v-data-footer .v-icon {
    color: black !important;
}

/* h1 {
    -webkit-text-stroke: 0.8px #fff;
} */
</style>