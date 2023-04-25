 
<template>
    <v-container>
        <v-overlay v-if="isOverlayLoading" class="align-center justify-center">
            <v-progress-circular color="white" indeterminate size="64" width="7"></v-progress-circular>
        </v-overlay>
        <div>
            <h1 class="font-weight-medium">Outbox</h1>
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
                class="mx-3 table-style" :items="!!inboxListData ? inboxListData : []" :loading="isLoading"
                :loading-text="isLoading ? 'Loading... Please wait' : ''" @click:row="rowClick">
                <template v-slot:item.num="{ index }">
                    {{ index + 1 }}
                </template>
                <template v-slot:item.assignedTo="{ item }">
                    <span>{{ item.roleCode }} - {{ item.assignedToName }}</span>
                </template>
            </v-data-table>

        </v-card>



        <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-overlay v-if="isOverlayLoading" class="align-center justify-center">
                    <v-progress-circular color="white" indeterminate size="64" width="7"></v-progress-circular>
                </v-overlay>
                <v-toolbar color="cyan darken-2" class="white--text">
                    <v-btn icon dark @click="dialogDetail = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Surat</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-container>
                    <v-row>
                        <v-col md="7">
                            <div class="my-5">
                                <h3>Riwayat Surat</h3>
                                <v-divider></v-divider>
                            </div>
                            <div>

                                <v-timeline dense align-top>

                                    <v-timeline-item v-for="itemDetail, index in historyListData.header" :key="index"
                                        color="cyan darken-2" :icon="itemDetail.completed ? 'mdi-check' : 'mdi-sync'"
                                        size="small" fill-dot>
                                        <v-card class="elevation-3">
                                            <v-card-title class="text-h6">
                                                <v-row>
                                                    <v-col md="4">
                                                        <h4>{{ itemDetail.unitFrom }} </h4>
                                                    </v-col>
                                                    <v-col md="8" class="text-end">
                                                        <v-chip medium color="default" outlined class="ma-2">
                                                            {{
                                                                momentJsFormating(itemDetail.createdDate, 1)
                                                            }}
                                                        </v-chip>
                                                        <v-chip medium color="default" outlined class="ma-2">
                                                            {{ itemDetail.typeText }}
                                                        </v-chip>
                                                    </v-col>
                                                </v-row>
                                            </v-card-title>
                                            <v-card-text class="font-weight-medium">
                                                <div v-if="itemDetail.menu == 'NADINE'"> {{ itemDetail.descriptionAction
                                                }}</div>
                                                <div v-else>
                                                    Surat telah di Disposisi oleh Ir. Iman Kristian Sinulingga(SDB) kepada
                                                    <ul>
                                                        <li v-for="itemDetails, index in detailHistory(itemDetail)"
                                                            :key="index">
                                                            {{ itemDetails.name }} ({{ itemDetails.unitTo }})
                                                        </li>
                                                    </ul>
                                                    <br>
                                                    <div class="my-2">
                                                        <span>Catatan:</span>
                                                        <i>
                                                            <p> {{ itemDetail.catatan }}</p>
                                                        </i>
                                                    </div>
                                                </div>

                                            </v-card-text>

                                        </v-card>


                                    </v-timeline-item>
                                </v-timeline>


                            </div>
                        </v-col>
                        <v-col md="5">
                            <div class="my-5">
                                <h3>Detail Surat</h3>
                                <v-divider></v-divider>
                            </div>
                            <v-card>
                                <v-card-text>
                                    <v-list three-line subheader class="my-5">
                                        <v-list-item>
                                            <v-list-item-content>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Nomor Agenda
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.nomorAgenda }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Nomor Surat
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.nomorSurat }} </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1">Tgl.Penerimaan
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{
                                                        momentJsFormating(detailDataRow.tglPenerimaanDisplayText, 2) }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1">Tgl.Surat
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ momentJsFormating(detailDataRow.tglSurat, 2) }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1">Sifat Surat
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.sifatSurat }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Dari </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.dari }}
                                                    </v-list-item-title>
                                                </div>

                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Kepada </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.kepada }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Isi Ringkasan
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.isiRingkasan }}
                                                    </v-list-item-title>
                                                </div>

                                            </v-list-item-content>
                                        </v-list-item>

                                    </v-list>
                                </v-card-text>
                            </v-card>
                        </v-col>


                    </v-row>
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
    data() {
        return {
            isOverlayLoading: false,
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
            listItemsReciver: {
                teruskan: [],
                disposisi: []
            },
            isLoading: false,
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
                { text: 'Assigned To', value: 'unitAssignedTo' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            },
            description: "",
            recipient: "",
            selectedType: "",
            listLocalUserData: [],
            userLocalData: [],
            parsed: false,
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
            disabledModalButtonSave: false,
            historyListData: {
                header: [],
                subHeader: []
            },
            trackingId: ""
        }
    },
    methods: {
        detailHistory(item) {
            // Returns true to show content for completed steps, false for others
            return this.historyListData.subHeader.filter((e) => e.unitFrom === item.unitFrom)
                .map((e) => { return e });
        },
        subHeaderDataTo(unitAssignedTo) {
            var data = this.historyListData.subHeader.filter((e) => e.parentTo === unitAssignedTo)
                .map((e) => { return e });
            return data;
        },
        loadingIndicator(unitAssignedTo) {
            var data = this.historyListData.subHeader.filter((e) => e.unitAssignedFrom === unitAssignedTo)
                .map((e) => { return e });
            return data;
        },
        momentJsFormating(dateValue, dateType) {
            moment.locale('id');
            var newDate = "";
            if (dateType == 1) {
                newDate = moment(dateValue).format("dddd,Do MMMM YYYY, HH:mm");
            } else if (dateType == 2) {
                newDate = moment(dateValue).format("dddd,Do MMMM YYYY");
            }
            return newDate;
        },
        async getHistoryHeader() {
            try {
                this.loadingUploadButton = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + 'history', { params: { trackingId: this.trackingId } });
                var dataVal = !!response ? response.data : [];

                this.historyListData.header = dataVal.header;
                this.historyListData.subHeader = dataVal.headerDetail;
            } catch (error) {
                console.log(error);
            }
        },
        async submit() {
            var listData = [];
            var maxDataFromLocal = (parseInt(this.detailDataList.maxData));
            var newNumber = maxDataFromLocal + 1;
            this.disabledModalButtonSave = true;
            if (this.recipient) {
                this.recipient.forEach((element, key) => {
                    var newData = {
                        trackingid: this.detailDataRow.trackingid,
                        // agendaNumber: this.detailDataRow.agendaNumber,
                        actionType: this.detailDataRow.type,
                        from: this.listLocalUserData.roleCode,
                        to: element.value,
                        // number: this.detailDataRow.number,
                        note: this.description,
                        createdBy: this.listLocalUserData.employeeId,
                        createdDate: new moment(this.dateAction).locale('id'),
                        actionDate: new moment(this.dateAction).locale('id'),
                        // actionFollowUp: this.selectedType,
                        // sequence: newNumber, // auto increment
                        // actionFollowUpDescription: this.description
                    }
                    newNumber++;
                    listData.push(newData);
                });
            }

            try {

                var formdata = new FormData();
                this.loadingUploadButton = true;
                formdata.append("listData", JSON.stringify(listData));
                await axios.post(process.env.VUE_APP_SERVICE_URL + 'inbox/create', formdata);            // var unknown = data.filter((e) => e.status === 'info').map((e) => {
                this.responseAlert.color = 'cyan darken-2';
                this.responseAlert.message = "Data berhasil tersimpan dan masuk ke Outbox";
                this.loadingUploadButton = false;
                this.isShowAlert = true;
                // await this.getSettings(this.detailDataRow.agendaNumber);
                this.dialogDetail = false;
                this.getOutbox();
                this.disabledModalButtonSave = false;

            } catch (error) {
                console.log(error);
            }

        },
        async getEmployeeParentChild() {
            try {

                if (this.listLocalUserData) {
                    var param = { params: { roleCode: this.listLocalUserData.roleCode, roleLevel: this.listLocalUserData.roleLevel } };
                    var responsesParent = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee/parent", param);
                    var listParent = [];
                    var listLevel = [];
                    if (responsesParent != undefined) {
                        responsesParent.data.parent.forEach(element => {
                            if (this.listLocalUserData.employeeId != element.employeeId) {
                                listParent.push({
                                    value: element.employeeId,
                                    text: element.employeeId + " - " + element.name + " - " + element.roleCode
                                });
                            }
                        });
                        responsesParent.data.level.forEach(element => {
                            if (this.listLocalUserData.employeeId != element.employeeId) {
                                listLevel.push({
                                    value: element.employeeId,
                                    text: element.employeeId + " - " + element.name + " - " + element.roleCode
                                });
                            }
                        });
                    }
                    this.listItemsReciver.teruskan = listLevel;
                    this.listItemsReciver.disposisi = listParent;

                }
            } catch (error) {
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }

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
        clearFormDialog() {
            this.selectedType = "";
            this.description = "";
            this.recipient = "";
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
                employeeId: this.listLocalUserData.employeeId,
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
                assignedFrom: this.listLocalUserData.roleCode
            };
            this.filter.searchingParams = remappingParam;

            await this.getOutbox();
        },
        async getOutbox() {
            try {
                this.isOverlayLoading = true;
                this.isLoading = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "outbox", { params: { searchingParams: this.filter.searchingParams } });
                this.inboxListData = !!response ? response.data : [];
                const state = {
                    data: !!response ? response.data : []
                }
                this.inboxListData.forEach((item, i) => {
                    item.indexNumber = i + 1;
                });
                await this.getEmployeeParentChild();

                this.isLoading = false;
                this.isShowTable = true;
                this.isOverlayLoading = false;
            } catch (error) {
                this.isOverlayLoading = false;
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }
        },
        async getSettings(agendaNumber) {

            try {
                var formdata = new FormData();
                this.loadingUploadButton = true;
                formdata.append("agendaNumber", agendaNumber);
                var response = await axios.post(process.env.VUE_APP_SERVICE_URL + 'inbox/log', formdata);
                this.detailDataList = !!response ? response.data : [];

            } catch (error) {
                console.log(error);
            }
        },
        async rowClick(row) {
            this.detailDataRow = row;
            this.dateAction = moment(row.receiptDate).format('YYYY-MM-DD');
            this.userDefault = this.listLocalUserData.name;
            this.dialogDetail = true;
            this.selectedType = row.actionFollowUp;
            this.trackingId = row.trackingId;
            this.isOverlayLoading = true;
            await this.getHistoryHeader();
            this.clearFormDialog();
            this.isOverlayLoading = false;
        },
        selectedTypeEvnt() {
            this.recipient = [];
            if (this.selectedType != 'ARSIP') {
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
                if (this.likesAllFruit) {
                    this.filter.sifatSurat = []
                } else {
                    this.filter.sifatSurat = this.$store.state.lookup.lookups['type'];
                }
            })
        },
    },
    async created() {
        this.isOverlayLoading = true;
        var data = JSON.parse(localStorage.getItem('userData'));
        this.listLocalUserData = data.user;
        this.getHistoryHeader();
        await this.searching();
        this.isOverlayLoading = false;
    },
    computed: {
        ...mapGetters(['inboxs', 'settings', 'lookups']),
        latterType() {
            return this.$store.state.lookup.lookups['type'];
        },
        actionFollowUp() {
            return this.$store.state.lookup.lookups['action'];
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
    }
}
</script> 

<style lang="css" scoped>
.table-style>>>tbody tr:hover {
    cursor: pointer;
    background: #0097A7 !important;
    color: white;
}
</style>