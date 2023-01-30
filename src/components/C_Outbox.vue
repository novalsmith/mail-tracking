 
<template>
    <v-container>
        <div>
            <h2>Outbox</h2>
        </div>
        <v-divider></v-divider>
        <v-card class="my-5">
            <v-card-title>Filter
                <v-spacer></v-spacer>
                <v-btn class="mr-4 white--text" color="cyan darken-2" small dark @click="advanceSearch(false)"
                    v-if="isAdvanceSearch">
                    <v-icon>mdi-arrow-down-thin</v-icon>
                    Advance Search
                </v-btn>
                <v-btn v-else class="mr-4 white--text" color="blue-grey" text small dark @click="advanceSearch(true)">
                    <v-icon>mdi-arrow-up-thin</v-icon>
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
                :search="search" :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''">
                <template v-slot:item="{ item, index }">
                    <tr class="rowColor" @click="rowClick(item)">
                        <td>{{ index + 1}}</td>
                        <td>{{ item.agendaNumber }}</td>
                        <td>{{ item.number }}</td>
                        <td>{{ item.receiptDate }}</td>
                        <td>{{ item.realDate }}</td>
                        <td>{{ item.typeName }}</td>
                        <td>{{ item.fromName }}</td>
                        <td>{{ item.toName }}</td>
                        <td>{{ item.note }}</td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>



        <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar color="cyan darken-2" class="white--text">
                    <v-btn icon dark @click="dialogDetail = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Agenda - {{ detailDataRow.agendaNumber }} - Perihal - {{
                        detailDataRow.note
                    }}</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-container>
                    <v-main class="my-5">
                        <h3> <v-icon class="mx-3">mdi-file-outline</v-icon> Transaksi Surat</h3>
                        <form class="my-10">


                            <v-row>
                                <v-col md="4">
                                    <v-text-field :disabled="true" v-model="userDefault" label="Dari"
                                        required></v-text-field>
                                </v-col>
                                <v-col md="4">
                                    <v-dialog ref="dialog" v-model="modalDate" :return-value.sync="date" persistent
                                        width="290px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field v-model="date" label="Tanggal Tindak Lanjut"
                                                prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="date" type="date" scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn text color="primary" @click="modalDate = false">
                                                Cancel
                                            </v-btn>
                                            <v-btn text color="primary" @click="$refs.dialog.save(date)">
                                                OK
                                            </v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-col>
                                <v-col md="4">
                                    <v-select :items="listType" v-model="selectedType" @change="selectedTypeEvnt"
                                        label="Tindak Lanjut"></v-select>
                                </v-col>
                                <v-col md="12">

                                    <div v-if="isReciverShow">
                                        <v-combobox :items="listItemsReciver" label="Kepada" multiple
                                            chips></v-combobox>
                                    </div>

                                    <div>
                                        <v-textarea outlined name="input-7-4" label="Keterangan" value=""></v-textarea>

                                    </div>
                                </v-col>
                                <v-col md="12">
                                    <v-btn class="mr-4 white--text" color="cyan darken-2" @click="submit">
                                        <v-icon>mdi-check</v-icon> Submit
                                    </v-btn>
                                    <v-btn text class="mr-4 white--text" color="blue-grey" @click="clear">
                                        <v-icon>mdi-cached</v-icon> Clear
                                    </v-btn>
                                </v-col>
                            </v-row>

                        </form>
                    </v-main>
                    <v-divider></v-divider>
                    <v-list three-line subheader class="my-5">
                        <h3> <v-icon class="mx-3">mdi-history</v-icon> Log History </h3>

                        <v-list-item v-for="items in detailDataList">
                            <v-list-item-content v-if="items.createdDate != null">
                                <v-list-item-title> <v-btn dark x-small color="cyan darken-2" outlined fab>{{
                                    items.sequence
                                }} </v-btn> {{
    items.createdBy
}}</v-list-item-title>
                                <v-list-item-subtitle> {{ items.toName }} - {{ items.createdDate }} - {{
                                    items.note
                                }}</v-list-item-subtitle>
                                <v-list-item-subtitle>Tanggal : {{ items.createdDate }}</v-list-item-subtitle>
                                <v-list-item-subtitle>Keterangan: {{ items.note }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>

                    </v-list>
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
                { text: 'No', value: 'trackingid' },
                { text: 'No. Agenda', value: 'agendaNumber' },
                { text: 'Tgl. Penerimaan', value: 'receiptDate' },
                { text: 'No. Surat', value: 'number' },
                { text: 'Tgl. Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'typeName' },
                { text: 'Dari', value: 'fromName' },
                { text: 'Kepada', value: 'toName' },
                { text: 'Keterangan', value: 'note' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            }

        }
    },
    methods: {
        submit() { },
        async getEmployeeParentChild() {
            try {
                var userData = JSON.parse(localStorage.getItem('userData'));
                if (userData && userData.user) {
                    var responsesParent = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee/" + userData.user.roleCode);
                    var listParent = [];
                    if (responsesParent != undefined) {
                        responsesParent.data.forEach(element => {
                            if (userData.user.employeeId != element.employeeId) {
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
        async getInbox() {
            try {
                this.isLoading = true;
                var userData = JSON.parse(localStorage.getItem('userData'));
                if (userData && userData.user) {
                    // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                    // var responseAll = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking");
                    // this.allTrackingData = responseAll != undefined ? responseAll : [];
                    // var url = userData.user.level == 0 ? "tracking" : "tracking/" + userData.user.roleCode;
                    var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking/show/" + userData.user.roleCode);


                    this.listData = !!response ? response.data : [];


                    // var lsitInboxData = {
                    //     allTrackingData: this.allTrackingData,
                    //     listParentChild: listParent,
                    //     trackingDataByRole: []
                    // };
                    // this.$store.dispatch('inboxs', lsitInboxData);
                    this.isShowTable = true;
                }
                this.isLoading = false;

            } catch (error) {
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }
        },
        async getSettings() {

            this.$store.dispatch('settings', this.themeColoring);
        },
        rowClick(row) {
            this.dialogDetail = true;
            const filteredList = this.allTrackingData.data.filter((e) => e.agendaNumber === row.agendaNumber)
                .map((e) => { return e });

            this.detailDataRow = row;
            // console.log(row); 
            // var dateChanges = new Date(row.receiptDate); 
            this.date = moment(String(row.receiptDate)).format('YYYY-MM-DD');
            // console.log(resDate);
            // this.date = new Date(row.receiptDate);
            this.detailDataList = filteredList;
            var listData = JSON.parse(localStorage.getItem('userData'));
            this.userDefault = listData.user.name;
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
            await this.getInbox();
        },
        submit() {
            this.$v.$touch()
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
    },
    created() {
        this.getSettings();
        this.getInbox();
        // this.getEmployeeParentChild();
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
        },

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
</style>