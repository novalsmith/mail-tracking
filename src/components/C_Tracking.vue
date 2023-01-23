 
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
                Nadine
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                    hide-details></v-text-field>
                <v-spacer></v-spacer>
                <v-btn @click="dialogUpload = true" small color="cyan darken-2 mx-2" class="white--text">
                    <v-icon>mdi-upload</v-icon>
                    Upload</v-btn>
                <v-btn @click="notready" small color="cyan darken-2" class="white--text"> <v-icon>mdi-download</v-icon>
                    Template</v-btn>
            </v-card-title>
            <v-data-table multi-sort :headerProps="headerprops" class="row-pointer" :headers="headers" :items="listData"
                :search="search" @click:row="rowClick" :loading="isLoading"
                :loading-text="isLoading ? 'Loading... Please wait' : ''">
                <template v-slot:item="{ item, index }">
                    <tr class="rowColor">
                        <td>{{ index + 1}}</td>
                        <td>{{ item.agendaNumber }}</td>
                        <td>{{ item.receiptDate }}</td>
                        <td>{{ item.realDate }}</td>
                        <td>{{ item.typeName }}</td>
                        <td>{{ item.fromName }}</td>
                        <td>{{ item.toName }}</td>
                        <td>{{ item.descriptionName }}</td>

                    </tr>
                </template>
            </v-data-table>
        </v-card>
        <v-row justify="center">
            <v-dialog v-model="dialogUpload" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="text-h5">Upload Surat NADINE</span>
                    </v-card-title>
                    <v-card-text>

                        <form class="mt-5">
                            <v-file-input label="Browse File" :rules="rules" accept="image/png, image/jpeg, image/bmp"
                                v-model="uploadedValue" outlined chips truncate-length="50"
                                prepend-icon="mdi-paperclip"></v-file-input>

                            <!-- <v-file-input multiple :rules="rules" accept="image/png, image/jpeg, image/bmp"
                                placeholder="Pick an avatar" prepend-icon="mdi-camera" label="Avatar"></v-file-input> -->

                        </form>
                        <v-btn color="cyan darken-2" class="white--text" @click="processUpload">
                            <v-icon>mdi-cloud-arrow-up-outline</v-icon> Submit
                        </v-btn>

                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn color="cyan darken-2" text @click="dialogUpload = false">
                            Close
                        </v-btn>

                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>

<script>
import axios from 'axios';
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import { mapGetters } from 'vuex';
export default {
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
            isLoading: true,
            alertNotready: false,
            search: "",
            listData: [],
            uploadedValue: [],
            dialogUpload: false,
            isShowAlert: false,
            responseAlert: {
                message: "",
                color: ""
            },
            headers: [
                { text: 'No', value: 'trackingid' },
                { text: 'Agenda', value: 'agendaNumber' },
                { text: 'Terima', value: 'receiptDate' },
                { text: 'Tanggal Surat', value: 'realDate' },
                { text: 'Sifat Surat', value: 'typeName' },
                { text: 'Dari', value: 'fromName' },
                { text: 'Kepada', value: 'toName' },
                { text: 'Ket', value: 'descriptionName' }
            ],
            headerprops: {
                "sort-icon": "mdi-arrow-up"
            }
        }
    },
    methods: {
        async getTracking() {
            try {
                // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                var response = await axios.get(process.env.VUE_APP_SERVICE_URL + "tracking");
                this.listData = response.data;
                this.$store.dispatch('trackings', response.data);
                this.isLoading = false;
            } catch (error) {
                console.log(error);
                this.isLoading = false;
                this.responseAlert.message = 'Something wrong, please refresh the page to fix this issue. detail : ' + error.message;
                this.responseAlert.color = "red";
                this.isShowAlert = true;
            }
        },
        async getSettings() {

            this.$store.dispatch('trackings', this.themeColoring);
        },
        rowClick(row) {
            this.notready();
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
        processUpload() {
            console.log(this.uploadedValue);
        }
    },
    created() {
        this.getSettings();
        this.getTracking();
    },
    computed: {
        ...mapGetters(['trackings', 'settings']),
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
</style>