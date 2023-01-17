<template>
    <form>
        <v-card class="mx-auto my-15" max-width="500" outlined elevation="5">
            <v-card-title>
                Login Mail Tracking Minerba
            </v-card-title>

            <v-card-text class="text--primary">


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
            password: ''

        }
    },
    methods: {
        login() {

            try {

                // console.log(process.env.BASE_URL);
                // await axios.get('http://localhost:1412/pegawai');
                // const user = JSON.parse(localStorage.getItem('user'));
                // const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InNhbXBsZUBnbWFpLmNvbSIsImlhdCI6MTY2NDY4MDU1NiwiZXhwIjoxNjY0NjgxOTk2fQ.oNFr5l-miXKkX0CAjeM08Kd2qc1Sr0gV7x9Qk18oFgA';  // axios.defaults.baseURL = "http://localhost:8080/";
                // const response = axios.create({
                //     baseURL: "http://localhost:8080/pegawai",
                //     withCredentials: false,
                //     headers: {
                //         Accept: 'application/json',
                //         'Content-Type': 'application/json',
                //         Authorization: `Bearer ${token}`
                //     }
                // });
                const param = { "nip": this.nip, "password": this.password }
                axios.post("otentikasi", param).then(res => {
                    var userDatas = res.data;

                    this.token = res.data.access_token;
                    localStorage.setItem('token', res.data.access_token);
                    localStorage.setItem('darkMode', this.$store.state.settings['isDarkMode']);
                    localStorage.setItem('userData', JSON.stringify(userDatas));
                    localStorage.setItem('isLogin', this.token != "" ? true : false);
                    // var listData = this.$store.state.users['userData'];
                    // listData.data = 12345;
                    // const userDatas2 = {
                    //     data: userDatas,
                    // }
                    // // console.log(listData);
                    // this.$store.dispatch('users', userDatas2);

                    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                    // this.$router.push('/');
                    this.$router.push("/").catch(() => { })
                        .then(() => { this.$router.go() });

                });

            } catch (error) {
                console.log(error);
            }
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