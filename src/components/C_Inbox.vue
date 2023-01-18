 
<template>
    <div>
        <v-card>
            <v-container>
                <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2" v-model="alertNotready"
                    elevation="2" icon="mdi-information-outline" border="left" dismissible
                    transition="scale-transition">
                    Sorry, this feature is not ready yet - <strong>Under Maintenance!</strong>
                </v-alert>
            </v-container>
            <v-card-title>
                Inbox
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                    hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="listData" :search="search" @click:row="rowClick">
                <template v-slot:item.trackingid="{ index }">
                    {{ index + 1 }}
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialogDetail" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar color="cyan darken-2" class="white--text">
                    <v-btn icon dark @click="dialogDetail = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Agenda - {{ detailDataRow.agendaNumber }}</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-container>
                    <v-main class="my-5">
                        <h3> <v-icon class="mx-3">mdi-file-outline</v-icon> Transaksi Surat</h3>
                        <form class="my-10">


                            <v-row>
                                <v-col md="4">
                                    <v-text-field :disabled="true" v-model="userDefault" :error-messages="dariErrors"
                                        label="Dari" required @input="$v.dari.$touch()"
                                        @blur="$v.dari.$touch()"></v-text-field>
                                </v-col>
                                <v-col md="4">
                                    <v-dialog ref="dialog" v-model="modalDate" :return-value.sync="date" persistent
                                        width="290px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field v-model="date" label="Picker in dialog"
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
                                    <v-select :items="listType" label="Jenis"></v-select>
                                </v-col>
                                <v-col md="12">
                                    <v-textarea outlined name="input-7-4" label="Keterangan" value=""></v-textarea>
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
                                <v-list-item-subtitle>To : {{ items.toName }} - {{ items.createdDate }} - {{
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
var maxlength = 18;
export default {
    mixins: [validationMixin],
    validations: {
        dari: { required, maxLength: maxLength(maxlength) },
        password: { required, maxLength: maxLength(maxlength) },
    },
    data() {
        return {
            date: new Date().toISOString().substr(0, 10),
            menuDate: false,
            modalDate: false,
            dialogDetail: false,
            alertNotready: false,
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
            headers: [
                { text: 'No', value: 'trackingid' },
                { text: 'Agenda', value: 'agendaNumber' },
                { text: 'Terima', value: 'receiptDate' },
                { text: 'Tanggal Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'typeName' },
                { text: 'Dari', value: 'fromName' },
                { text: 'Ket', value: 'descriptionName' }
            ],
        }
    },
    methods: {
        submit() { },
        async getUsers() {
            try {
                var userData = JSON.parse(localStorage.getItem('userData'));
                console.log(userData.user);
                if (userData && userData.user) {
                    var responseAll = await axios.get("tracking");
                    this.allTrackingData = responseAll;
                    var response = await axios.get("tracking/" + userData.user.roleCode);
                    this.listData = response.data;


                    this.$store.dispatch('tracking', response.data);
                }

            } catch (error) {
                console.log(error);
            }
        },
        async getSettings() {

            this.$store.dispatch('settings', this.themeColoring);
        },
        rowClick(row) {
            const filteredList = this.allTrackingData.data.filter((e) => e.agendaNumber === row.agendaNumber)
                .map((e) => { return e });
            this.dialogDetail = true;
            this.detailDataRow = row;
            this.detailDataList = filteredList;
            var listData = JSON.parse(localStorage.getItem('userData'));
            this.userDefault = listData.user.name;
            console.log(this.detailDataList);
        },
        rowEditClick(row) {
            // this.notready();
        },
        rowDeleteClick(row) {
            // this.notready();
        },
        notready() {
            this.alertNotready = true;
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
    },
    created() {
        this.getSettings();
        this.getUsers();
    },
    computed: {
        ...mapGetters(['tracking', 'settings']),
        dariErrors() {
            const errors = []
            if (!this.$v.dari.$dirty) return errors
            !this.$v.dari.maxLength && errors.push('dari must be at most ' + maxlength + ' characters long')
            !this.$v.dari.required && errors.push('dari is required.')
            return errors
        },
        passwordErrors() {
            const errors = []
            if (!this.$v.password.$dirty) return errors
            !this.$v.password.maxLength && errors.push('Password must be at most ' + maxlength + ' characters long')
            !this.$v.password.required && errors.push('Password is required.')
            return errors
        },
        isValid() {
            if ((this.dari != '' || this.dari.length > maxLength) && (this.password != '' || this.dari.length > maxLength)) {
                return false;
            } else {
                return true;
            }
        }
    }
}
</script>