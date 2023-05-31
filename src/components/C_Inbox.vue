 
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Inbox</h1>
        </div>
        <v-overlay v-if="isOverlayLoading" class="align-center justify-center">
            <v-progress-circular color="white" indeterminate size="64" width="7"></v-progress-circular>
        </v-overlay>
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
                                        item-text="name" item-value="id" label="Duplication?"></v-select>
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
                                    <v-text-field dense outlined clearable v-model="filter.ket" label="Isi Ringkasan"
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
                class="table-style" :items="!!inboxListData ? inboxListData : []" :loading="isLoading"
                :loading-text="isLoading ? 'Loading... Please wait' : ''" @click:row="rowClick" :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
                <template v-slot:item.num="{ index }">
                    {{ index + 1 }}
                </template>
                <template v-slot:item.unitAssignedTo="{ item, index }">

                    <v-tooltip bottom v-if="item.isExistsInOutbox != null">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn fab small color="cyan darken-2" dark v-bind="attrs" v-on="on">
                                <v-icon class="mx-1">mdi-account-check</v-icon>
                            </v-btn>
                        </template>
                        <span>Assigned</span>
                    </v-tooltip>
                    <v-tooltip bottom v-else-if="item.actionType == 'ARSIP'">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn fab small color="green lighten-1" dark v-bind="attrs" v-on="on">
                                <v-icon class="mx-1">mdi-check-circle-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Arsip</span>
                    </v-tooltip>
                    <v-tooltip bottom v-else>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn fab small color="orange" dark v-bind="attrs" v-on="on">
                                <v-icon class="mx-1">mdi-alert-outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Unassigned</span>
                    </v-tooltip>

                </template>
                <template v-slot:item.isDuplication="{ item, index }">
                    <v-chip small v-if="item.isDuplication == '1'" color="orange" dark>
                        Yes
                    </v-chip>
                    <v-chip small v-else color=" cyan darken-2" dark>
                        No
                    </v-chip>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-overlay v-if="isOverlayLoading" class="align-center justify-center">
                    <v-progress-circular color="white" indeterminate size="64" width="7"></v-progress-circular>
                </v-overlay>
                <v-toolbar :color="detailDataRow.isDuplication == '1' ? 'orange' : 'cyan darken-2'" class="white--text">
                    <v-btn icon dark @click="closeModal(false)">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Surat <span v-if="detailDataRow.isDuplication == '1'">
                            (Duplikasi)</span></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn v-if="isShowEditButton" class="mr-4 white--text" color="orange" @click="isEdit = false">
                        <v-icon>mdi-pencil</v-icon> Edit
                    </v-btn>
                </v-toolbar>
                <v-container>
                    <v-main class="my-5">
                        <v-col md="12" v-if="isShowAlertDialogDetail">
                            <v-alert text dense close-icon="mdi-close-circle-outline" :color="responseAlert.color"
                                elevation="2" icon="mdi-information-outline" border="left" dismissible
                                transition="scale-transition">
                                {{ responseAlert.message }}
                            </v-alert>

                        </v-col>
                        <h3> <v-icon class="mx-3">mdi-file-outline</v-icon> Transaksi Surat</h3>
                        <form class="my-10">
                            <v-row>
                                <v-col md="4">
                                    <v-text-field :disabled="true" dense outlined v-model="userDefault"
                                        label="Dari"></v-text-field>
                                </v-col>
                                <v-col md="4">
                                    <v-dialog ref="dialog" v-model="modalDate"
                                        :return-value.sync="detailInboxModalDialog.actionDate" persistent width="290px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field :error-messages="tglTindaklanjutError" required
                                                @input="$v.detailInboxModalDialog.actionDate.$touch()" outlined dense
                                                v-model="detailInboxModalDialog.actionDate" label="Tanggal Tindak Lanjut"
                                                prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"
                                                :disabled="isEdit"></v-text-field>
                                        </template>
                                        <v-date-picker @input="$v.detailInboxModalDialog.actionDate.$touch()"
                                            :error-messages="tglTindaklanjutError" dense
                                            v-model="detailInboxModalDialog.actionDate" type="date" scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="modalDate = false">
                                                Cancel
                                            </v-btn>
                                            <v-btn text color="primary"
                                                @click="$refs.dialog.save(detailInboxModalDialog.actionDate)">
                                                OK
                                            </v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col md="4" v-if="!isStaf">
                                    <v-select :error-messages="tindakLanjutError" required
                                        @input="$v.detailInboxModalDialog.selectedType.$touch()" :disabled="isEdit"
                                        :items="actionFollowUp" item-text="name" item-value="code"
                                        v-model="detailInboxModalDialog.selectedType" dense outlined
                                        @change="selectedTypeEvnt" label="Tindak Lanjut"></v-select>
                                </v-col>
                                <v-col md="12">

                                    <div v-if="isReciverShow && !isStaf">
                                        <v-combobox :error-messages="kepadaError" required
                                            @input="$v.detailInboxModalDialog.recipient.$touch()" :disabled="isEdit"
                                            :items="detailInboxModalDialog.selectedType == 'TERUSKAN' ? listItemsReciver.teruskan : listItemsReciver.disposisi"
                                            dense outlined v-model="detailInboxModalDialog.recipient" label="Kepada"
                                            multiple chips></v-combobox>
                                    </div>

                                    <div>
                                        <v-textarea :disabled="isEdit" v-model="detailInboxModalDialog.notes" dense outlined
                                            name="input-7-4" label="Catatan" value=""></v-textarea>

                                    </div>
                                </v-col>
                                <v-col md="12" v-if="!isArsipSurat">
                                    <v-btn :disabled="isValid || disabledModalButtonSave || isEdit" class="mr-4 white--text"
                                        color="cyan darken-2" @click="submit">
                                        <v-icon>mdi-check</v-icon> Submit
                                    </v-btn>
                                    <v-btn text class="mr-4 white--text" :disabled="isEdit || isValid" color="blue-grey"
                                        @click="clearFormDialog">
                                        <v-icon>mdi-cached</v-icon> Clear
                                    </v-btn>
                                </v-col>
                            </v-row>

                        </form>
                    </v-main>
                    <v-row>
                        <v-col md="7">
                            <div class="my-5">
                                <h3>Riwayat Surat</h3>
                                <v-divider></v-divider>
                            </div>
                            <div>

                                <v-timeline dense align-top>

                                    <v-timeline-item v-for=" itemDetail, index  in  historyListData.header " :key="index"
                                        color="cyan darken-2" icon="mdi-check" size="small" fill-dot>
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
                                                    {{ itemDetail.descriptionAction }}
                                                    <ul>
                                                        <li v-for=" itemDetails, index  in  detailHistory(itemDetail) "
                                                            :key="index">
                                                            {{ itemDetails.name }} ({{ itemDetails.unitTo }})
                                                        </li>
                                                    </ul>
                                                    <br>
                                                    <div class="my-2">
                                                        <span>Catatan:</span> <small v-if="itemDetail.updatedDate != ''">*
                                                            Catatan terakhir
                                                            diubah pada {{
                                                                momentJsFormating(itemDetail.updatedDate, 2) }} </small>
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
                                                    <span v-html="detailDataRow.isiRingkasan">
                                                    </span>

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

export default {
    mixins: [validationMixin],
    validations: {
        detailInboxModalDialog: {
            selectedType: { required },
            notes: { required },
            recipient: { required },
            actionDate: { required },
        }
    },
    data() {
        return {
            isOverlayLoading: false,
            e6: 22,
            e13: 2,
            step1Complete: 1,
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
                { text: 'Status', value: 'unitAssignedTo' },
                { text: 'Duplication', value: 'isDuplication' }

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
                    name: "All"
                },
                {
                    id: "Y",
                    name: "Yes"
                },
                {
                    id: "N",
                    name: "No"
                }
            ],
            isArsipSurat: false,
            disabledModalButtonSave: false,
            trackingId: "",
            historyListData: {
                header: [],
                subHeader: []
            },
            isLoadingIndicator: [],
            panelActive: [],
            subPanelActive: [],
            loadingUploadButton: false,
            isStaf: false,
            activeStep: 2,
            itemsTimeline: [
                {
                    id: 1,
                    color: 'cyan darken-2',
                    icon: 'mdi-check'
                },
                {
                    id: 2,
                    color: 'green',
                    icon: 'mdi-sync'
                },
            ],
            detailInboxModalDialog: {
                selectedType: "",
                notes: "",
                recipient: [],
                actionDate: moment().format("YYYY-MM-DD")
            },
            detailInboxOriginalDataModalDialog: {
                selectedType: "",
                notes: "",
                recipient: []
            },
            isEdit: false,
            isShowEditButton: false,
            rules: [
                value => {
                    if (value) return true

                    return 'Tidak boleh kosong'
                },
            ],
            requiredKepada(value) {
                if (value instanceof Array && value.length == 0) {
                    return 'Tidak boleh kosong';
                }
                return !!value || 'Tidak boleh kosong';
            },
        }
    },
    methods: {
        detailHistory(item) {
            // Returns true to show content for completed steps, false for others
            return this.historyListData.subHeader.filter((e) => e.unitFrom === item.unitFromCode)
                .map((e) => { return e });
        },
        async submit() {
            var params = {
                inboxData: []
            };


            if (this.detailInboxModalDialog.recipient.length > 0) {
                this.detailInboxModalDialog.recipient.forEach((element, key) => {
                    var newData = {
                        inboxId: this.detailDataRow.inboxId,
                        trackingId: this.detailDataRow.trackingId,
                        from: this.listLocalUserData.employeeId,
                        to: element.value,
                        description: this.detailInboxModalDialog.notes,
                        actionType: this.detailInboxModalDialog.selectedType,
                        actionDate: this.detailInboxModalDialog.actionDate,
                        createdBy: this.listLocalUserData.employeeId,
                        createdDate: new moment(new Date).locale('id'),
                        inboxType: "EDIT"
                    };

                    params.inboxData.push(newData);

                });

                this.detailInboxOriginalDataModalDialog.recipient.forEach((element, key) => {
                    var newData = {
                        inboxId: this.detailDataRow.inboxId,
                        trackingId: this.detailDataRow.trackingId,
                        from: this.listLocalUserData.employeeId,
                        to: element.value,
                        description: this.detailInboxModalDialog.notes,
                        actionType: this.detailInboxModalDialog.selectedType,
                        actionDate: this.detailInboxModalDialog.actionDate,
                        createdBy: this.listLocalUserData.employeeId,
                        createdDate: new moment(new Date).locale('id'),
                        inboxType: "ORIGINAL"
                    };

                    params.inboxData.push(newData);

                });
            } else {
                var newData = {
                    inboxId: this.detailDataRow.inboxId,
                    trackingId: this.detailDataRow.trackingId,
                    from: this.listLocalUserData.employeeId,
                    to: this.listLocalUserData.employeeId,
                    description: this.detailInboxModalDialog.notes,
                    actionType: (this.isStaf ? "ARSIP" : this.detailInboxModalDialog.selectedType),
                    actionDate: this.detailInboxModalDialog.actionDate,
                    createdBy: this.listLocalUserData.employeeId,
                    createdDate: new moment(new Date).locale('id'),
                    inboxType: "EDIT"
                };

                params.inboxData.push(newData);
            }

            console.log(params);
            try {

                var formdata = new FormData();
                this.loadingUploadButton = true;
                formdata.append("listData", JSON.stringify(params));

                this.isOverlayLoading = true;
                await axios.post(process.env.VUE_APP_SERVICE_URL + 'inbox/create', formdata);
                this.responseAlert.color = 'cyan darken-2';
                this.responseAlert.message = "Data berhasil tersimpan dan masuk ke Outbox";
                this.loadingUploadButton = false;
                this.isShowAlert = true;
                this.isOverlayLoading = false;
                this.searching();
                this.dialogDetail = false;
            } catch (error) {
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
                this.isOverlayLoading = false;
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
            this.detailInboxModalDialog = {
                selectedType: "",
                notes: "",
                recipient: [],
                actionDate: moment().format("YYYY-MM-DD")
            }
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
                unitTo: this.listLocalUserData.roleCode
            };
            this.filter.searchingParams = remappingParam;
            await this.getInbox();
        },
        async getInbox() {
            try {
                this.isOverlayLoading = true;
                this.isLoading = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "inbox", { params: { searchingParams: this.filter.searchingParams } });
                this.inboxListData = !!response ? response.data : [];
                const state = {
                    data: !!response ? response.data : []
                }
                this.$store.dispatch('inboxs', state);
                this.inboxListData.forEach((item, i) => {
                    item.indexNumber = i + 1;
                });
                await this.getEmployeeParentChild();

                this.isLoading = false;
                this.isShowTable = true;
                this.isOverlayLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
                this.isOverlayLoading = true;
            }
        },
        async getInboxById() {
            try {
                var remappingParam = {
                    trackingId: this.trackingId
                };

                this.filter.searchingParams = remappingParam;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "inbox/show", { params: { searchingParams: this.filter.searchingParams } });
                var listtemp = !!response ? response.data : [];
                if (listtemp.length > 0) {

                    var listRecipientTmp = [];
                    var datarecipient = listtemp.filter((e) => e.trackingId === this.trackingId
                        && e.from === this.listLocalUserData.employeeId
                    )
                        .map((e) => { return e });
                    datarecipient.forEach(element => {
                        listRecipientTmp.push({
                            value: element.to,
                            text: element.to + " - " + element.nameTo + " - " + element.unitAssignedTo
                        });
                    });
                    this.detailInboxModalDialog.selectedType = datarecipient.length > 0 ? datarecipient[0].actionType : "";
                    this.detailInboxModalDialog.notes = datarecipient.length > 0 ? datarecipient[0].description : "";
                    this.detailInboxModalDialog.actionDate = datarecipient.length > 0 ? moment(datarecipient[0].actionDate).format('YYYY-MM-DD') : moment.format('YYYY-MM-DD');
                    this.detailInboxModalDialog.recipient = listRecipientTmp;

                    this.detailInboxOriginalDataModalDialog.selectedType = datarecipient.length > 0 ? datarecipient[0].actionType : "";
                    this.detailInboxOriginalDataModalDialog.notes = datarecipient.length > 0 ? datarecipient[0].description : "";
                    this.detailInboxOriginalDataModalDialog.recipient = listRecipientTmp;
                    this.detailInboxOriginalDataModalDialog.actionDate = datarecipient.length > 0 ? moment(datarecipient[0].actionDate).format('YYYY-MM-DD') : null;
                    this.isArsipSurat = (this.detailInboxModalDialog.selectedType == 'ARSIP' ? true : false);
                }

            } catch (error) {
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }
            this.isOverlayLoading = false;
        },
        closeModal(isClose) {
            this.dialogDetail = isClose;
        },
        async rowClick(row) {
            console.log(row);
            this.isOverlayLoading = true;
            this.dialogDetail = true;

            if (row.isExistsInOutbox != null) {
                this.isEdit = true;
                this.isShowEditButton = true;
            } else {
                this.isEdit = false;
                this.isShowEditButton = false;
            }

            this.detailDataRow = row;
            this.userDefault = this.listLocalUserData.name;
            this.trackingId = row.trackingId;
            this.clearFormDialog();
            this.getHistoryHeader();
            this.selectedTypeEvnt();
            this.getInboxById();

            this.isShowAlert = false;

        },
        selectedTypeEvnt() {
            this.recipient = [];
            if (this.detailInboxModalDialog.selectedType != 'ARSIP') {
                this.isReciverShow = true;
            } else {
                this.isReciverShow = false;
                this.detailInboxModalDialog.recipient = [];
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
                newDate = moment(dateValue).format("dddd, Do MMMM YYYY, HH:mm");
            } else if (dateType == 2) {
                newDate = moment(dateValue).format("dddd, Do MMMM YYYY");
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
            }
        },
        closeDialogDetail() {
            this.dialogDetail = false;
            this.isEdit = false;
        },


    },
    async created() {
        this.isOverlayLoading = true;
        var data = JSON.parse(localStorage.getItem('userData'));
        this.listLocalUserData = data.user;
        this.getHistoryHeader();
        await this.searching();
        this.isStaf = (parseInt(this.listLocalUserData.roleLevel) == 5 || parseInt(this.listLocalUserData.roleLevel) == 0 ? true : false);
        this.isOverlayLoading = false;
    },
    computed: {
        ...mapGetters(['inboxs', 'settings', 'lookups']),
        latterType() {
            return this.$store.state.lookup.lookups['type'];
        },
        getRowClass(item, index, field) {
            // console.log(item);
            // if (item.isDuplication == 1) {
            //     // console
            //     // .log(inboxListData");
            //     return 'duplicationRow'; // or any other class name for the row background color
            // } else {
            //     return 'mx-3 table-style';
            // }

            var listDatas = this.inboxListData.reduce((classes, item, index) => {

                if (parseInt(item.isDuplication) === 1) {
                    classes[index] = 'mx-3 duplicationRow table-style';
                }
                return classes;
            }, {});

            // console.log(listDatas[0]);
            return listDatas[0];

        },
        actionFollowUp() {
            var data = this.$store.state.lookup.lookups['action'];
            if (parseInt(this.listLocalUserData.roleLevel) == 5) {
                return data.filter((e) => e.code === "ARSIP")
                    .map((e) => { return e });
            } else if (parseInt(this.listLocalUserData.roleLevel) == 1) {
                return data.filter((e) => e.code != "TERUSKAN")
                    .map((e) => { return e });
            } else {
                return data.filter((e) => e.code != "")
                    .map((e) => { return e });
            }
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
        allStepsCompleted() {
            // Check if all steps are completed
            var aa = this.historyListData.header.every(step => step.completed);
            return this.historyListData.header.every(step => step.completed)
        },
        tglTindaklanjutError() {
            const errors = []
            if (!this.$v.detailInboxModalDialog.actionDate.$dirty) return errors
            !this.$v.detailInboxModalDialog.actionDate.required && errors.push('Tidak boleh kosong')
            return errors
        },
        tindakLanjutError() {
            const errors = []
            if (!this.$v.detailInboxModalDialog.selectedType.$dirty) return errors
            !this.$v.detailInboxModalDialog.selectedType.required && errors.push('Tidak boleh kosong')
            return errors
        },
        kepadaError() {
            const errors = []
            if (!this.$v.detailInboxModalDialog.recipient.$dirty) return errors
            !this.$v.detailInboxModalDialog.recipient.required && errors.push('Kepada tidak boleh kosong')
            return errors
        },
        catatanError() {
            const errors = []
            if (!this.$v.detailInboxModalDialog.notes.$dirty) return errors
            !this.$v.detailInboxModalDialog.notes.required && errors.push('Tidak boleh kosong')
            return errors
        },
        isValid() {

            if (!this.isStaf) {
                if (this.detailInboxModalDialog.selectedType == "ARSIP") {
                    if (this.detailInboxModalDialog.actionDate != null && this.detailInboxModalDialog.selectedType != "") {
                        return false;
                    } else {
                        return true;
                    }
                }
                else if (this.detailInboxModalDialog.actionDate != null && (this.detailInboxModalDialog.recipient.length > 0 && this.detailInboxModalDialog.selectedType != "")) {
                    return false;
                } else {
                    return true;
                }
            } else {
                this.detailInboxModalDialog.selectedType = "ARSIP";
                this.detailInboxModalDialog.recipient = [];
                if (this.detailInboxModalDialog.actionDate != null) {
                    return false;
                } else {
                    return true;
                }
            }

        }
    }
}
</script> 

<style lang="css" scoped>
.table-style>>>tbody tr:hover {
    cursor: pointer;
    background: #0097A7 !important;
    color: white;
}

.divider-active {
    border-width: 3px;
    background: #0097A7 !important;
}

.show-content {
    display: block !important;
}

.duplicationRow>>>tbody tr {
    cursor: pointer;
    background: #1f4d50 !important;
    color: white;
}
</style>