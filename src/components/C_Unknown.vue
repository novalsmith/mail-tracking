 
<template>
    <v-container>
        <div>
            <h1 class="font-weight-medium">Unknown Box</h1>
        </div>
        <!-- <v-divider></v-divider> -->
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

                <template v-slot:item.unitTo="{ item, index }">

                    <!-- <v-btn v-if="item.unitTo ?? ''" small color="cyan darken-2" dark> {{ item.unitTo }}</v-btn> -->
                    <v-chip v-if="item.unitTo" color="cyan darken-2" dark>
                        {{ item.unitTo }} <v-icon class="mx-1">mdi-check-circle-outline</v-icon>
                    </v-chip>
                    <v-chip v-else color="orange" dark>
                        {{ item.unitTo }} Unknown <v-icon class="mx-1">mdi-alert-outline</v-icon>
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
                                <v-btn v-if="!!item.unitTo && item.number == detailUnknownData.number"
                                    :disabled="disabledUnknownButton" @click="moveToInbox(item, true)" small color="orange"
                                    class="white--text">
                                    Batalkan <v-icon class="mx-1">mdi-remove-outline</v-icon>
                                </v-btn>
                                <v-btn v-else :disabled="disabledUnknownButton" @click="moveToInbox(item, false)" small
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
            headers: [
                { text: 'No', value: 'num' },
                { text: 'No. Agenda', value: 'agendaNumber' },
                { text: 'Tgl. Penerimaan', value: 'receiptDate' },
                { text: 'No. Surat', value: 'number' },
                { text: 'Tgl. Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'type' },
                { text: 'Dari', value: 'from' },
                { text: 'Kepada', value: 'to' },
                { text: 'Isi Ringkasan', value: 'note', with: '10%' },
                { text: 'Tujuan', value: 'unitTo', with: '10%' },

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
            loadingUploadButton: false


        }
    },
    methods: {
        async getUnitParent() {
            try {

                this.isLoadingUnknown = true;
                if (this.userDefault) {
                    var params = {
                        params: { roleCode: this.userDefault.roleCode, isAdmin: this.userDefault.roleLevel }
                    };

                    var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "unknown/parent", params);
                    this.listUnitUnknown = !!response ? response.data : [];
                    if (!!this.listUnitUnknown) {
                        console.log(this.listUnitUnknown);
                        console.log(this.detailUnknownData);
                        var listData = this.listUnitUnknown.filter((e) => e.number.toLowerCase() == this.detailUnknownData.number.toLowerCase())
                            .map((e) => { return e });
                        console.log(listData);
                        this.listUnitUnknown = (listData.length == 0 ? this.listUnitUnknown : listData);

                    }

                    this.isLoadingUnknown = false;
                }

            } catch (error) {
                this.isLoadingUnknown = false;
            }
        },
        async getUnknown() {
            try {
                this.isLoading = true;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "unknown");
                this.listUnknownData = !!response ? response.data : [];
                this.listUnknownData.forEach((item, i) => {
                    item.indexNumber = i + 1;
                });
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
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
        },
        async searching() {
            this.isShowTable = true;
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
            }
            // this.getUnknown();
        },
        async submit() {

            try {


                var listData = {
                    trackingid: row.trackingid,
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
            this.detailUnknownData = row;
            await this.getUnitParent();
            this.dialogUnknown = true;
            this.searchUnknown = row.unitTo;


        },
        async moveToInbox(item, isCancel) {

            var row = this.detailUnknownData;
            console.log(row);
            var params = [
                {
                    trackingid: row.trackingid,
                    to: isCancel ? null : item.code,
                    from: row.createdBy,
                    createdDate: moment().format('YYYY-MM-DD'),
                    createdBy: row.createdBy

                }
            ]
            console.log(params);

            try {
                if (params) {
                    var formdata = new FormData();
                    this.disabledUnknownButton = true;
                    formdata.append("listData", JSON.stringify(params));
                    // formdata.append("trackingid", row.trackingid);

                    var ResonseData = await axios.post(process.env.VUE_APP_SERVICE_URL + 'unknown/create', formdata);          // var unknown = data.filter((e) => e.status === 'info').map((e) => {
                    console.log(ResonseData);

                    this.isShowAlert = true;
                    this.responseAlert.color = 'cyan darken-2';
                    this.responseAlert.message = "Data Berhasil Dipindahkan ke Unit " + item.code;
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
            } catch (error) {
                this.isShowAlert = true;
                this.isLoading = false;
                console.log(error);
                this.dialogUnknown = false;
                this.disabledUnknownButton = false;
                this.responseAlert.color = 'error';
                this.responseAlert.message = error.response.data.message;
            }


        },
        itemRowBackground(item) {

            return item.level == '2' || item.level == '3' ? 'font-weight-medium cyan lighten-5' : '';
        }
    },
    async created() {
        await this.getSettings();
        await this.getUnknown();
        await this.getUnitParent();

    },
    computed: {
        ...mapGetters(['inboxs', 'settings', 'lookups']),
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
        }
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