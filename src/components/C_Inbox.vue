 
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Inbox</h1>
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
                                    <v-text-field dense outlined clearable v-model="filter.ket" label="Catatan"
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

                    <v-btn fab small v-if="item.inboxOutboxId" color="cyan darken-2" dark>
                        <v-icon class="mx-1">mdi-account-check</v-icon>
                    </v-btn>

                    <v-btn fab small v-else color="orange" dark>
                        <v-icon class="mx-1">mdi-alert-outline</v-icon>
                    </v-btn>
                </template>
            </v-data-table>

        </v-card>



        <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar color="cyan darken-2" class="white--text">
                    <v-btn icon dark @click="dialogDetail = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Detail Surat</v-toolbar-title>
                    <v-spacer></v-spacer>

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
                                    <v-text-field :disabled="true" dense outlined v-model="userDefault" label="Dari"
                                        required></v-text-field>
                                </v-col>
                                <v-col md="4">
                                    <v-dialog ref="dialog" v-model="modalDate" :return-value.sync="dateAction" persistent
                                        width="290px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field outlined dense v-model="dateAction" label="Tanggal Tindak Lanjut"
                                                prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker dense v-model="dateAction" type="date" scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="modalDate = false">
                                                Cancel
                                            </v-btn>
                                            <v-btn text color="primary" @click="$refs.dialog.save(dateAction)">
                                                OK
                                            </v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col md="4">
                                    <v-select :items="actionFollowUp" item-text="name" item-value="code"
                                        v-model="selectedType" dense outlined @change="selectedTypeEvnt"
                                        label="Tindak Lanjut"></v-select>
                                </v-col>
                                <v-col md="12">

                                    <div v-if="isReciverShow">
                                        <v-combobox
                                            :items="selectedType == 'TERUSKAN' ? listItemsReciver.teruskan : listItemsReciver.disposisi"
                                            dense outlined v-model="recipient" label="Kepada" multiple chips></v-combobox>
                                    </div>

                                    <div>
                                        <v-textarea v-model="description" dense outlined name="input-7-4" label="Keterangan"
                                            value=""></v-textarea>

                                    </div>
                                </v-col>
                                <v-col md="12">
                                    <v-btn :disabled="disabledModalButtonSave" class="mr-4 white--text"
                                        color="cyan darken-2" @click="submit">
                                        <v-icon>mdi-check</v-icon> Submit
                                    </v-btn>
                                    <v-btn text class="mr-4 white--text" color="blue-grey" @click="clear">
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
                            <!-- <template> -->
                            <div>
                                <v-stepper v-model="e13" vertical>
                                    <v-stepper-step step="1" complete>
                                        Name of step 1
                                    </v-stepper-step>

                                    <v-stepper-content step="1">
                                        <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                                        <v-btn color="primary" @click="e13 = 2">Continue</v-btn>
                                        <v-btn text>Cancel</v-btn>
                                    </v-stepper-content>

                                    <v-stepper-step step="2" complete complete-icon="mdi-check">Name of step
                                        2</v-stepper-step>

                                    <v-stepper-content step="2">
                                        <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                                        <v-btn color="primary" @click="e13 = 3">Continue</v-btn>
                                        <v-btn text>Cancel</v-btn>
                                    </v-stepper-content>

                                    <v-stepper-step :rules="[() => false]" step="3">
                                        Ad templates
                                        <small>Alert message</small>
                                    </v-stepper-step>

                                    <v-stepper-content step="3">
                                        <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                                        <v-btn color="primary" @click="e13 = 4">Continue</v-btn>
                                        <v-btn text>Cancel</v-btn>
                                    </v-stepper-content>

                                    <v-stepper-step step="4">View setup instructions</v-stepper-step>

                                    <v-stepper-content step="4">
                                        <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                                        <v-btn color="primary" @click="e13 = 1">Continue</v-btn>
                                        <v-btn text>Cancel</v-btn>
                                    </v-stepper-content>
                                </v-stepper>
                            </div>
                            <!-- </template> -->
                            <v-expansion-panels v-model="panelActive" multiple>

                                <v-expansion-panel v-for="(subValueData, index) in historyListData.header" :key="index"
                                    class="my-1">
                                    <v-expansion-panel-header expand-icon="mdi-chevron-down">
                                        <h4>
                                            <v-btn fab x-small
                                                :color="loadingIndicator(subValueData.unitAssignedTo).length > 0 ? 'cyan darken-2' : 'blue-grey lighten-2'"
                                                dark class="mx-2">
                                                <v-icon v-if="loadingIndicator(subValueData.unitAssignedTo).length > 0">
                                                    mdi-check
                                                </v-icon>
                                                <v-icon v-else>
                                                    mdi-sync
                                                </v-icon>
                                            </v-btn>
                                            {{ subValueData.unitAssignedFrom }} - {{ subValueData.nameFrom }}
                                        </h4>
                                    </v-expansion-panel-header>
                                    <v-expansion-panel-content>
                                        <v-container>

                                            {{
                                                momentJsFormating(subValueData.createdDate)
                                            }} - {{ subValueData.description }}

                                            <v-expansion-panels multiple class="my-2">
                                                <v-expansion-panel
                                                    v-for="(subValueData1, index) in subHeaderDataTo(subValueData.unitAssignedFrom)"
                                                    :key="index" class="my-1">
                                                    <v-expansion-panel-header expand-icon="mdi-chevron-down">
                                                        <h4>
                                                            <v-btn fab x-small
                                                                :color="loadingIndicator(subValueData1.unitAssignedTo).length > 0 ? 'cyan darken-2' : 'blue-grey lighten-2'"
                                                                dark class="mx-2">
                                                                <v-icon
                                                                    v-if="loadingIndicator(subValueData1.unitAssignedTo).length > 0">
                                                                    mdi-check
                                                                </v-icon>
                                                                <v-icon v-else>
                                                                    mdi-sync
                                                                </v-icon>
                                                            </v-btn>
                                                            {{ subValueData1.unitAssignedTo }} - {{ subValueData1.nameTo }}
                                                        </h4>
                                                    </v-expansion-panel-header>
                                                    <v-expansion-panel-content>
                                                        <v-container>

                                                            {{
                                                                momentJsFormating(subValueData1.createdDate)
                                                            }}
                                                            {{ subValueData1.description
                                                            }}

                                                            <v-expansion-panels multiple class="my-2">
                                                                <v-expansion-panel
                                                                    v-for="(subValueData2, index) in subHeaderDataTo(subValueData1.unitAssignedTo)"
                                                                    :key="index" class="my-1">
                                                                    <v-expansion-panel-header
                                                                        expand-icon="mdi-chevron-down">
                                                                        <h4>
                                                                        <!-- <v-btn fab x-small color="cyan darken-2" dark
                                                                                class="mx-2">
                                                                                <v-icon>
                                                                                    mdi-check
                                                                                </v-icon>
                                                                                                                                                            </v-btn> -->
                                                                            <v-btn fab x-small
                                                                                :color="loadingIndicator(subValueData2.unitAssignedTo).length > 0 ? 'cyan darken-2' : 'blue-grey lighten-2'"
                                                                                dark class="mx-2">
                                                                                <v-icon
                                                                                    v-if="loadingIndicator(subValueData2.unitAssignedTo).length > 0">
                                                                                    mdi-check
                                                                                </v-icon>
                                                                                <v-icon v-else>
                                                                                    mdi-sync
                                                                                </v-icon>
                                                                            </v-btn>
                                                                            {{ subValueData2.unitAssignedTo }} -
                                                                            {{ subValueData2.nameTo }}
                                                                        </h4>
                                                                    </v-expansion-panel-header>
                                                                    <v-expansion-panel-content>
                                                                        <v-container>

                                                                            {{
                                                                                momentJsFormating(subValueData2.createdDate)
                                                                            }}- {{ subValueData2.description }}

                                                                            <v-expansion-panels multiple class="my-2">
                                                                                <v-expansion-panel
                                                                                    v-for="(subValueData3, index) in subHeaderDataTo(subValueData2.unitAssignedTo)"
                                                                                    :key="index" class="my-1">
                                                                                    <v-expansion-panel-header
                                                                                        expand-icon="mdi-chevron-down">
                                                                                        <h4>
                                                                                            <v-btn fab x-small
                                                                                                :color="loadingIndicator(subValueData3.unitAssignedTo).length > 0 ? 'cyan darken-2' : 'blue-grey lighten-2'"
                                                                                                dark class="mx-2">
                                                                                                <v-icon
                                                                                                    v-if="loadingIndicator(subValueData3.unitAssignedTo).length > 0">
                                                                                                    mdi-check
                                                                                                </v-icon>
                                                                                                <v-icon v-else>
                                                                                                    mdi-sync
                                                                                                </v-icon>
                                                                                            </v-btn>
                                                                                            <span
                                                                                                v-if="subValueData3.levelTo = 5">Staf
                                                                                                - {{ subValueData3.nameTo }}
                                                                                            </span>
                                                                                            <span v-else>{{
                                                                                                subValueData3.unitAssignedTo
                                                                                                - subValueData3.nameTo
                                                                                            }}</span>
                                                                                        </h4>
                                                                                    </v-expansion-panel-header>
                                                                                    <v-expansion-panel-content>
                                                                                        {{
                                                                                            momentJsFormating(subValueData2.createdDate)
                                                                                        }}- {{
    subValueData2.description }}
                                                                                    </v-expansion-panel-content>
                                                                                </v-expansion-panel>
                                                                            </v-expansion-panels>
                                                                        </v-container>
                                                                    </v-expansion-panel-content>
                                                                </v-expansion-panel>
                                                            </v-expansion-panels>
                                                        </v-container>

                                                    </v-expansion-panel-content>
                                                </v-expansion-panel>
                                            </v-expansion-panels>
                                        </v-container>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>

                            <!-- tab -->


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
                                                    <v-list-item-title> {{ detailDataRow.agendaNumber }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Nomor Surat
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.number }} </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1">Tgl.Penerimaan
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.receiptDate }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1">Tgl.Surat
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.realDate }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1">Sifat Surat
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.type }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Dari </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.from }}
                                                    </v-list-item-title>
                                                </div>

                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Keada </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.to }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Isi Ringkasan
                                                    </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.description }}
                                                    </v-list-item-title>
                                                </div>
                                                <div class="my-3">
                                                    <v-list-item-subtitle class="my-1"> Tindakan </v-list-item-subtitle>
                                                    <v-list-item-title> {{ detailDataRow.actionType }}
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
                { text: 'Assigned From', value: 'unitAssignedFrom' },
                { text: 'Tgl.Tindak lanjut', value: 'tglTindaklanjut' },
                { text: 'Tindakan', value: 'actionTypeValue' },
                { text: 'Status', value: 'unitAssignedTo' }

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
            trackingId: "",
            historyListData: {
                header: [],
                subHeader: []
            },
            isLoadingIndicator: [],
            panelActive: [],
            subPanelActive: [],
            loadingUploadButton: false
        }
    },
    methods: {
        async submit() {
            console.log(this.detailDataRow);
            var params = {
                inboxData: [],
                outboxData: [],
                historyData: []
            };

            this.disabledModalButtonSave = true;
            if (this.recipient) {
                var nameValue = this.actionFollowUp.filter((e) => e.code === this.selectedType)
                    .map((e) => { return name = e.name })[0];

                this.recipient.forEach((element, key) => {
                    console.log(element);
                    var newData = {
                        trackingId: this.detailDataRow.trackingId,
                        from: this.listLocalUserData.employeeId,
                        to: element.value,
                        description: this.description,
                        actionType: this.selectedType,
                        actionDate: new moment(new Date).locale('id'),
                        createdBy: this.listLocalUserData.employeeId,
                        createdDate: new moment(new Date).locale('id'),
                    };

                    params.inboxData.push(newData);

                });

                params.outboxData = {
                    trackingId: this.detailDataRow.trackingId,
                    inboxId: this.detailDataRow.inboxId,
                    catatan: this.description,
                    tindaklanjut: this.selectedType,
                    tglTindaklanjut: new moment(new Date).locale('id'),
                    createdBy: this.listLocalUserData.employeeId,
                    createdDate: new moment(new Date).locale('id'),
                };

                params.historyData = {
                    trackingId: this.detailDataRow.trackingId,
                    inboxId: this.detailDataRow.inboxId,
                    menu: 'INBOX',
                    type: this.selectedType,
                    description: "Surat telah di " + nameValue,
                    createdBy: this.listLocalUserData.employeeId,
                    createdDate: new moment(new Date).locale('id')
                };
            }
            try {

                var formdata = new FormData();
                this.loadingUploadButton = true;
                formdata.append("listData", JSON.stringify(params));
                await axios.post(process.env.VUE_APP_SERVICE_URL + 'inbox/create', formdata);            // var unknown = data.filter((e) => e.status === 'info').map((e) => {
                this.dialogDetail = false;
                this.responseAlert.color = 'cyan darken-2';
                this.responseAlert.message = "Data berhasil tersimpan dan masuk ke Outbox";
                this.loadingUploadButton = false;
                this.isShowAlert = true;
                this.dialogDetail = false;
                this.getInbox();
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
                    console.log(this.listItemsReciver);
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
                unitTo: this.listLocalUserData.roleCode
            };
            this.filter.searchingParams = remappingParam;
            console.log(this.filter.searchingParams);
            await this.getInbox();
        },
        async getInbox() {
            try {
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

            } catch (error) {
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }
        },
        async rowClick(row) {

            const filteredList = this.$store.state.inboxs['inboxs'].data.filter((e) => e.agendaNumber === row.agendaNumber)
                .map((e) => { return e });
            console.log(row);
            this.detailDataRow = row;
            this.dateAction = moment(row.receiptDate).format('YYYY-MM-DD');
            // this.detailDataList = filteredList;
            this.userDefault = this.listLocalUserData.name;
            this.dialogDetail = true;
            // this.description = row.note;
            this.selectedType = row.actionFollowUp;
            this.trackingId = row.trackingId;
            await this.getHistoryHeader();
            this.clearFormDialog();

        },

        selectedTypeEvnt() {
            console.log(this.selectedType);
            this.recipient = [];
            if (this.selectedType != 'ARSIP') {
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
        momentJsFormating(dateValue) {
            return moment.utc(dateValue).format("MMMM Do YYYY, h:mm:ss");
        },
        async getHistoryHeader() {
            try {
                this.loadingUploadButton = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + 'history', { params: { trackingId: this.trackingId } });
                var dataVal = !!response ? response.data : [];

                this.historyListData.header = dataVal.header.filter((e) => parseInt(e.level) === 0 || parseInt(e.level) === 1 || parseInt(e.level) === 2)
                    .map((e) => { return e });
                this.historyListData.subHeader = dataVal.headerDetail;
                console.log(this.historyListData.subHeader);
            } catch (error) {
                console.log(error);
            }
        },
    },
    async created() {
        var data = JSON.parse(localStorage.getItem('userData'));
        this.listLocalUserData = data.user;
        this.getHistoryHeader();
        await this.searching();

    },
    computed: {
        ...mapGetters(['inboxs', 'settings', 'lookups']),
        latterType() {
            return this.$store.state.lookup.lookups['type'];
        },
        actionFollowUp() {
            var data = this.$store.state.lookup.lookups['action'];
            if (parseInt(this.listLocalUserData.roleLevel) == 5) {
                data.filter((e) => e.code === "ARSIP")
                    .map((e) => { return e });
            }
            return data;
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

.divider-active {
    border-width: 3px;
    background: #0097A7 !important;
}

/* ::v-deep .v-stepper__step__step {
    width: 40px;
    height: 40px;
} */
</style>