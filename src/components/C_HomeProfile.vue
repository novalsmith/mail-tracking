<template>
    <v-row>
        <v-col lg="12" class="text--disabled">
            <h1 class="font-weight-medium">Welcome to Mail Track Minerba</h1>
            <span class="description" :color="color">Sebuah tools yang dirancang untuk membantu proses penelusuran
                (tracking) terhadap persuratan di lingkungan internal Direktorat Jenderal Mineral dan Batubara</span>

        </v-col>
        <v-col cols="3">
            <v-container>
                <v-card class="my-5">
                    <v-card-title class="text--disabled">Inbox
                    </v-card-title>
                    <v-card-text>
                        <h1 class="font-weight-medium">35</h1>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>
        <v-col cols="3">
            <v-container>
                <v-card class="my-5">
                    <v-card-title class="text--disabled">Outbox
                    </v-card-title>
                    <v-card-text>
                        <h1 class="font-weight-medium">35</h1>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>
        <v-col cols="3">
            <v-container>
                <v-card class="my-5">
                    <v-card-title class="text--disabled">Nadine
                    </v-card-title>
                    <v-card-text>
                        <h1 class="font-weight-medium">300</h1>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-col>

        <v-col lg="12">
            <v-container>
                <v-card class="my-5">
                    <v-form>
                        <v-container>
                            <v-row>
                                <v-col cols="2" class="my-3">
                                    <h1 class="text--disabled font-weight-medium">Lacak Surat</h1>
                                </v-col>
                                <v-col cols="10" class="my-3">
                                    <v-text-field v-model="search" height="50" dense outlined clearable
                                        label="Silahkan masukan kata kunci pencarian" hide-details @keyup="getTracking"
                                        append-icon="mdi-magnify"></v-text-field>
                                </v-col>

                            </v-row>
                        </v-container>
                    </v-form>
                    <v-data-table v-show="search != '' ? true : false" multi-sort :headers="headers"
                        class="mx-3 table-style" :items="listData" :search="search" :loading="isLoading"
                        :loading-text="isLoading ? 'Loading... Please wait' : ''" @click:row="rowClick" :footer-props="{
                            showFirstLastPage: true,
                            firstIcon: 'mdi-arrow-collapse-left',
                            lastIcon: 'mdi-arrow-collapse-right',
                            prevIcon: 'mdi-minus',
                            nextIcon: 'mdi-plus'
                        }">
                        <template v-slot:item.num="{ index, item }">
                            {{ index + 1 }}
                        </template>
                        <template v-slot:item.to="{ item }">
                            <ul>
                                <li v-for="values in splitString(item.to)">
                                    {{ values }}</li>
                            </ul>
                        </template>
                        <template v-slot:item.isUnknown="{ item }">

                            <!-- <p v-else :class="'blue--text'">Tidak</p> -->
                            <v-chip small v-if="item.isUnknown == 'Y'" color="orange" dark>
                                Yes
                            </v-chip>
                            <v-chip small v-else color="cyan darken-2" dark>
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

                                            <v-timeline-item v-for="itemDetail, index in historyListData.header"
                                                :key="index" color="cyan darken-2" icon="mdi-check" size="small" fill-dot>
                                                <v-card class="elevation-3">
                                                    <v-card-title class="text-h6">
                                                        <v-row>
                                                            <v-col md="7">
                                                                <h5>{{ itemDetail.unitFrom }} </h5>
                                                            </v-col>
                                                            <v-col md="5" class="text-end">
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
                                                        <div v-if="itemDetail.menu == 'NADINE'"> {{
                                                            itemDetail.descriptionAction
                                                        }}</div>
                                                        <div v-else>
                                                            {{ itemDetail.descriptionAction }}
                                                            <ul>
                                                                <li v-for="itemDetails, index in detailHistory(itemDetail)"
                                                                    :key="index">
                                                                    {{ itemDetails.name }} ({{ itemDetails.unitTo }})
                                                                </li>
                                                            </ul>
                                                            <br>
                                                            <div class="my-2">
                                                                <span>Catatan:</span> <small
                                                                    v-if="itemDetail.updatedDate != ''">*
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
                                                            <v-list-item-title> {{ detailDataRow.nomorSurat }}
                                                            </v-list-item-title>
                                                        </div>
                                                        <div class="my-3">
                                                            <v-list-item-subtitle class="my-1">Tgl.Penerimaan
                                                            </v-list-item-subtitle>
                                                            <v-list-item-title> {{
                                                                momentJsFormating(detailDataRow.tglPenerimaanDisplayText, 2)
                                                            }}
                                                            </v-list-item-title>
                                                        </div>
                                                        <div class="my-3">
                                                            <v-list-item-subtitle class="my-1">Tgl.Surat
                                                            </v-list-item-subtitle>
                                                            <v-list-item-title> {{ momentJsFormating(detailDataRow.tglSurat,
                                                                2) }}
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
                                                            <v-list-item-subtitle class="my-1"> Kepada
                                                            </v-list-item-subtitle>
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

        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { mapState } from "vuex";
export default {
    name: "Hero",
    computed: {
        ...mapState(['settings'])
    },
    data() {
        return {
            color: "grey darken-2",
            searchValue: "",
            search: "",
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            },
            headers: [
                { text: 'No', value: 'num' },
                { text: 'No.Agenda', value: 'nomorAgenda' },
                { text: 'Tgl.Penerimaan', value: 'tglPenerimaanDisplayText' },
                { text: 'No.Surat', value: 'nomorSurat' },
                { text: 'Tgl.Surat', value: 'tglSuratDisplayText' },
                { text: 'Sifat Surat', value: 'sifatSurat' },
                { text: 'Dari', value: 'dari' },
                { text: 'Kepada', value: 'kepada', width: '15%' },
                { text: 'Isi Ringkasan', value: 'isiRingkasan' },
                { text: 'Keterangan', value: 'keterangan', width: '10%' },
                { text: 'Uploader', value: 'unitUploader', width: '5%' },
                { text: 'Duplication', value: 'isUnknown', width: '5%' },

            ],
            listData: [],
            isLoading: false,
            isOverlayLoading: false,
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
                unknownModelData: "",
                listUploaderData: ""
            },
            isShowTable: false,
            historyListData: {
                header: [],
                subHeader: []
            },
            isOverlayLoading: false,
            trackingId: "",
            dialogDetail: false,
            detailDataRow: [],
            loadingUploadButton: false
        }
    },
    methods: {
        async getData() {
            try {
                await axios.get(process.env.VUE_APP_SERVICE_URL + "employee");
            } catch (error) {
                console.log(error);
                this.isLoading = false;
            }
        },
        async getTracking() {
            try {
                this.isOverlayLoading = true;
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
                this.isOverlayLoading = false;
            } catch (error) {
                console.log(error);
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
                this.isOverlayLoading = false;
            }
        },
        rowClick(row) {
            this.detailDataRow = row;
            this.date = moment(String(row.receiptDate)).format('YYYY-MM-DD');
            this.userDefault = this.users.name;
            this.trackingId = row.trackingId;
            this.dialogDetail = true;
            this.getHistoryHeader();
        },
        async getHistoryHeader() {
            try {
                this.isOverlayLoading = true;
                this.loadingUploadButton = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + 'history', { params: { trackingId: this.trackingId } });
                var dataVal = !!response ? response.data : [];

                this.historyListData.header = dataVal.header;
                this.historyListData.subHeader = dataVal.headerDetail;
                this.isOverlayLoading = false;
            } catch (error) {
                console.log(error);
                this.isOverlayLoading = false;
            }
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
        detailHistory(item) {
            // Returns true to show content for completed steps, false for others
            return this.historyListData.subHeader.filter((e) => e.unitFrom === item.unitFromCode)
                .map((e) => { return e });
        },
    },
    created() {
        var listData = JSON.parse(localStorage.getItem('userData'));
        this.users = listData != undefined && listData.user ? listData.user : [];
        this.getData();
    }
}
</script>
 
<style>
.intro-text {
    font-size: 40px;
    font-weight: 600;
    font-family: 'Roboto';
    line-height: 40pt;
    /* color: #444444ba; */
}

p.description {
    font-size: 19px;
    margin: 32px 0px;
    /* color: #444444ba; */

}

/* h1 {
  -webkit-text-stroke: 0.8px #fff;
} */

/* 
.img-circle {
    border-radius: 3%;
} */
</style>