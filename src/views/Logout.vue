<template>
    <div>
        <h1>Logout</h1>
    </div>
</template>
<script>
import axios from 'axios';
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'
export default {
    mixins: [validationMixin],
    validations: {
        nip: { required, maxLength: maxLength(10) },
        password: { required, maxLength: maxLength(10) },
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
        logout() {
            try {

                localStorage.removeItem('token');
                localStorage.removeItem('isLogin');
                localStorage.removeItem('userData');
                this.$router.push("/").catch(() => { })
                    .then(() => { this.$router.go() });


            } catch (error) {
                console.log(error);
            }
        },
    },
    created() {
        this.logout();
    }
}
</script>