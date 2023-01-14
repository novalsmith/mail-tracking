<template>
    <div>
        <h1>Login Page</h1>
        <p>Your Token : </p>
        <p>{{token}}</p>
    </div>
</template>

<script>
import { AxiosAuth } from '../auth/http-common'
import axios from 'axios';
export default {
    data() {
        return {
            userData: [],
            token: ""
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
                const param = {"isWebAdmin": true }
                axios.post("otentikasi",param).then(res => {
                    this.userData = res.data;
                    // console.log(res.data.access_token);

                    this.token = res.data.access_token;
                    localStorage.setItem('token', res.data.access_token);
                    this.$store.dispatch('user', res.data.user);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
                    this.$router.push('/users');
                });

            } catch (error) {
                console.log(error);
            }
        }
    },
    created() {
        this.login();
    }
}
</script>