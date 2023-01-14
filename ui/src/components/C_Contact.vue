<template>
    <div>
        <v-row>
            <Breadcrumbs />
            <v-col md="12">
                <v-card class="mx-auto">
                    <v-img src="../assets/lahai3.jpeg" height="300px" dark>

                    </v-img>

                    <div class="my-2 py-2 text-center">
                        Hubungi Kami 
                        <v-btn small v-for="link in menu.topRight" :key="link.icon" :to="link.path" icon
                            class="circle mx-2" :color="settings.color">
                            <v-icon>{{ link.icon }}</v-icon> 
                        </v-btn>
                    </div>
                </v-card>
            </v-col>
            <v-col md="12">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15955.322473888407!2d132.1953263!3d-1.2749201!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x27b65878b4b1e193!2sGKI%20Lahai%20Roi%20Mefkajim%20Ayamaru!5e0!3m2!1sen!2sid!4v1666784962689!5m2!1sen!2sid"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </v-col>  
        </v-row>
    </div>
</template>
  
<script>
import { mapState } from "vuex";
import { validationMixin } from 'vuelidate'
import { required, maxLength, email } from 'vuelidate/lib/validators'
import Breadcrumbs from '@/components/C_Breadcrumbs.vue';
export default {
    mixins: [validationMixin],

    validations: {
        name: { required, maxLength: maxLength(10) },
        email: { required, email },
        pesan: { required },
        checkbox: {
            checked(val) {
                return val
            },
        },
    },

    data: () => ({
        menu: {},
        name: '',
        email: '',
        pesan: '',
        items: [
            'Item 1',
            'Item 2',
            'Item 3',
            'Item 4',
        ],
        checkbox: false,
    }),
    created() {
        this.getMenuData();
        this.setBreadcrumsData();
    },
    computed: {
        checkboxErrors() {
            const errors = []
            if (!this.$v.checkbox.$dirty) return errors
            !this.$v.checkbox.checked && errors.push('You must agree to continue!')
            return errors
        },
        selectErrors() {
            const errors = []
            if (!this.$v.select.$dirty) return errors
            !this.$v.select.required && errors.push('Item is required')
            return errors
        },
        nameErrors() {
            const errors = []
            if (!this.$v.name.$dirty) return errors
            !this.$v.name.maxLength && errors.push('Name must be at most 10 characters long')
            !this.$v.name.required && errors.push('Name is required.')
            return errors
        },
        emailErrors() {
            const errors = []
            if (!this.$v.email.$dirty) return errors
            !this.$v.email.email && errors.push('Must be valid e-mail')
            !this.$v.email.required && errors.push('E-mail is required')
            return errors
        },
        pesanErrors() {
            const errors = []
            if (!this.$v.pesan.$dirty) return errors
            !this.$v.pesan.required && errors.push('Pesan is required.')
 
            return errors
        },
        ...mapState(['settings'])
    },

    components: {
        Breadcrumbs
    },
    methods: {
        setBreadcrumsData() {
            const datas = {
                type: "informasi",
                items: [
                    {
                        text: 'Beranda',
                        disabled: false,
                        href: '/',
                        color: "#42A5F5"
                    },
                    {
                        text: 'Kontak',
                        disabled: true,
                        href: 'breadcrumbs_link_2',
                        color: "#BDBDBD"
                    },
                ],

            }
            this.$store.dispatch('breadcrumData', datas);

        },
        getMenuData() {
            this.menu = this.$store.state.settings['menu'];
        },
        submit() {
            this.$v.$touch()
        },
        clear() {
            this.$v.$reset()
            this.name = ''
            this.email = ''
            this.select = null
            this.checkbox = false
        },
    },
}
</script>