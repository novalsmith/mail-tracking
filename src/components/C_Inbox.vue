 
<template>
    <div>
        <v-card>
            <v-container>
                <v-alert text dense close-icon="mdi-close-circle-outline" :color="responseAlert.color"
                    v-model="isShowAlert" elevation="2" icon="mdi-information-outline" border="left" dismissible
                    transition="scale-transition">
                    {{ responseAlert.message }}
                </v-alert>
            </v-container>
            <v-card-title>
                Inbox
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                    hide-details></v-text-field>
            </v-card-title>
            <v-data-table multi-sort :headerProps="headerprops" :headers="headers" :items="listData" :search="search"
                :loading="isLoading" :loading-text="isLoading ? 'Loading... Please wait' : ''">
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
    </div>
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
            // date: new Date().toISOString().substr(0, 10),
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
            isLoading: true,
            responseAlert: {
                message: "",
                color: ""
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
        async getInbox() {
            try {
                var userData = JSON.parse(localStorage.getItem('userData'));
                if (userData && userData.user) {
                    // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                    var responseAll = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking");
                    this.allTrackingData = responseAll != undefined ? responseAll : [];
                    var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking/" + userData.user.roleCode);

                    var responsesParent = await axios.get(process.env.VUE_APP_SERVICE_URL + "employee/" + userData.user.roleCode);
                    var listParent = [];
                    if (responsesParent != undefined) {
                        responsesParent.data.forEach(element => {
                            if (userData.user.employeeId != element.employeeId) {
                                listParent.push(element.employeeId + "-" + element.name);
                            }
                        });
                    }

                    this.listData = response != undefined ? response.data : [];
                    this.listItemsReciver = listParent;

                    var lsitInboxData = {
                        allTrackingData: this.allTrackingData,
                        listParentChild: listParent,
                        trackingDataByRole: this.listData
                    };
                    this.$store.dispatch('inboxs', lsitInboxData);
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
        rowEditClick(row) {
            // this.notready();
        },
        rowDeleteClick(row) {
            // this.notready();
        },
        notready() {
            this.isShowAlert = true;
        },
        submit() {
            this.$v.$touch()
        },
        clear() {
            this.$v.$reset()
            this.name = ''
            this.dari = ''
            this.password = ''
        },
        selectedTypeEvnt() {
            if (this.selectedType != 'Arsipkan') {
                this.isReciverShow = true;
            } else {
                this.isReciverShow = false;
            }
        }
    },
    created() {
        this.getSettings();
        this.getInbox();
    },
    computed: {
        ...mapGetters(['inboxs', 'settings']),
        // dariErrors() {
        //     const errors = []
        //     if (!this.$v.dari.$dirty) return errors
        //     !this.$v.dari.maxLength && errors.push('dari must be at most ' + maxlength + ' characters long')
        //     !this.$v.dari.required && errors.push('dari is required.')
        //     return errors
        // },
        // passwordErrors() {
        //     const errors = []
        //     if (!this.$v.password.$dirty) return errors
        //     !this.$v.password.maxLength && errors.push('Password must be at most ' + maxlength + ' characters long')
        //     !this.$v.password.required && errors.push('Password is required.')
        //     return errors
        // },
        // isValid() {
        //     if ((this.dari != '' || this.dari.length > maxLength) && (this.password != '' || this.dari.length > maxLength)) {
        //         return false;
        //     } else {
        //         return true;
        //     }
        // }
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