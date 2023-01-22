<template>
    <form>
        <v-card class="mx-auto my-15" max-width="500" outlined elevation="5">
            <v-card-title class="justify-center">
                <img src="../assets/login.png" alt="" srcset="" width="80">
            </v-card-title>

            <v-card-title class="justify-center">
                Login Mail Tracking Minerba 
            </v-card-title>

            <v-card-text class="text--primary">
                <div>
                    <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2" v-model="alert"
                        elevation="2" icon="mdi-information-outline" border="left" dismissible
                        transition="scale-transition">
                        {{ response.fail }}
                    </v-alert>
                </div>

                <v-text-field v-model="nip" :error-messages="nipErrors" label="NIP" required @input="$v.nip.$touch()"
                    @blur="$v.nip.$touch()"></v-text-field>
                <v-text-field v-model="password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" required
                    @input="$v.password.$touch()" @blur="$v.password.$touch()" :type="show1 ? 'text' : 'password'"
                    name="input-10-1" label="Password" :error-messages="passwordErrors" counter
                    @click:append="show1 = !show1"></v-text-field>

            </v-card-text>

            <v-card-actions>
                <v-btn :disabled="isValid" text class="mr-4 white--text" color="cyan darken-2" @click="login">
                    <v-icon>mdi-lock-outline</v-icon> Login
                </v-btn>
                <v-btn text class="mr-4 white--text" color="blue-grey" @click="clear">
                    <v-icon>mdi-cached</v-icon> Clear
                </v-btn>
            </v-card-actions>
        </v-card>
    </form>
</template>

<script>
// import { AxiosAuth } from '../auth/http-common'
import axios from 'axios';
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
import { mapGetters } from 'vuex';
var maxlength = 18;
export default {
    mixins: [validationMixin],
    validations: {
        nip: { required, maxLength: maxLength(maxlength) },
        password: { required, maxLength: maxLength(maxlength) },
    },
    data() {
        return {
            nip: '',
            select: null,
            userData: [],
            token: "",
            show1: false,
            password: '',
            alert: false,
            response: {
                fail: ""
            }
        }
    },
    methods: {
        async login() {
            // console.log(axios.defaults.baseURL);
            const param = { "nip": this.nip, "password": this.password }
            await axios.post(process.env.VUE_APP_SERVICE_URL + "otentikasi", param).then(res => {
                var userDatas = res.data;

                this.token = res.data.access_token;
                localStorage.setItem('token', res.data.access_token);
                localStorage.setItem('darkMode', this.$store.state.settings['isDarkMode']);
                localStorage.setItem('userData', JSON.stringify(userDatas));
                localStorage.setItem('isLogin', this.token != "" ? true : false);
                localStorage.setItem('alertSuccessLogin', true);
                axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

                this.$router.push("/").catch(() => { })
                    .then(() => { this.$router.go() });

            }).catch(error => {
                this.alert = true;
                console.log(error.response);
                this.response.fail = error.response.data.message;

            });


        },
        submit() {
            this.$v.$touch()
        },
        clear() {
            this.$v.$reset()
            this.name = ''
            this.nip = ''
            this.password = ''
        },
    },
    computed: {
        ...mapGetters(['users', 'settings']),
        nipErrors() {
            const errors = []
            if (!this.$v.nip.$dirty) return errors
            !this.$v.nip.maxLength && errors.push('NIP must be at most ' + maxlength + ' characters long')
            !this.$v.nip.required && errors.push('NIP is required.')
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
            if ((this.nip != '' || this.nip.length > maxLength) && (this.password != '' || this.nip.length > maxLength)) {
                return false;
            } else {
                return true;
            }
        }
    },
}
</script>