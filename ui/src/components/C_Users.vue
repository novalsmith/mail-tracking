<template>
    <v-simple-table fixed-header height="300px">
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">
                        Name
                    </th>
                    <th class="text-left">
                        Calories
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in user">
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';
import Clogin from '@/components/C_Login.vue'
export default {
    data() {
        return {
            desserts: [
                {
                    name: 'Frozen Yogurt',
                    calories: 159,
                },
                {
                    name: 'Ice cream sandwich',
                    calories: 237,
                },
                {
                    name: 'Eclair',
                    calories: 262,
                },
                {
                    name: 'Cupcake',
                    calories: 305,
                },
                {
                    name: 'Gingerbread',
                    calories: 356,
                },
                {
                    name: 'Jelly bean',
                    calories: 375,
                },
                {
                    name: 'Lollipop',
                    calories: 392,
                },
                {
                    name: 'Honeycomb',
                    calories: 408,
                },
                {
                    name: 'Donut',
                    calories: 452,
                },
                {
                    name: 'KitKat',
                    calories: 518,
                },
            ],
            themeColoring:
            {
                color: "teal"
            }

            // userData: []
        }
    },
    methods: {
        async whatsAppMessage() {
            try {

                const phoneNumber = ["6282231161874", "6285341986152"];
                for (const key in phoneNumber) {

                    const param = {
                        "messaging_product": "whatsapp",
                        "recipient_type": "individual",
                        "to": phoneNumber[key],
                        "type": "template",
                        "template": {
                            "name": "e_warta",
                            "language": {
                                "code": "id"
                            }
                        }
                    };
                    console.log(param);
                    axios.defaults.headers.common['Authorization'] = `Bearer EAAJ5M6f6IIUBAFMZAqgr8HB4xrddUIctCNmqwueP4PAqoNSt7DGskA4xDbdzeE9k0piMMlG53iTRGCjDZBKBQCAhrJY3EZCj24s4iFk1paSL9SMcOvDxqnKGMqaPejuDo571geYH2fhdjXq5PyKRDijJKnpKsu89UfHaRlHvZATpyN0WJ7YgyIytCqR986Y1Vd8lrTolZCwZDZD`;
                    var response = await axios.post("https://graph.facebook.com/v15.0/102273206030676/messages", param);
                    // this.userData = response.data;
                    // this.$store.dispatch('user', response.data);
                    console.log(response);
                }
            } catch (error) {
                console.log(error.response);
                // console.log
                // if (error.response && error.response.data && error.response.data.error === "Expired token") {
                //     // Clogin.login();
                //     // this.$root.$refs.C_Login.login();
                //     // this.$refs.C_Login.login();
                //     // localStorage.removeItem('token');
                //     window.location.reload();

                // }
                // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
            }
        },
        async getUsers() {
            try {
                var response = await axios.get("pegawai");
                // this.userData = response.data;
                this.$store.dispatch('user', response.data);
            } catch (error) {
                console.log(error.response);
                // console.log
                // if (error.response && error.response.data && error.response.data.error === "Expired token") {
                //     // Clogin.login();
                //     // this.$root.$refs.C_Login.login();
                //     // this.$refs.C_Login.login();
                //     // localStorage.removeItem('token');
                //     window.location.reload();

                // }
                // axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
            }
        },
        async getSettings() {

            this.$store.dispatch('settings', this.themeColoring);
        }
    },
    created() {
        this.getSettings();
        this.getUsers();
        // this.whatsAppMessage(); //<-- for testing send the whatsapp message from API
    },
    computed: {
        ...mapGetters(['user', 'settings'])
        // user() {
        //     return this.$store.state.user
        // }
    }
}
</script>