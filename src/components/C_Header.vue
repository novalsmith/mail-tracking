 
<template>
    <div>
        <v-app-bar color="transparent" elevation="0" height="auto">

            <v-main>
                <div class="d-lg-none mb-10">
                    <v-row>
                        <!-- logo here -->
                        <v-img alt="Vuetify Name" v-if="settings.screenSize.type.islg" contain
                            src="../assets/login-mail-track.png" width="100%" max-width="30%" />

                        <v-img alt="Vuetify Name" v-if="settings.screenSize.type.ismd" max-width="10%" contain
                            src="../assets/login-mail-track.png" width="100%" />

                        <v-img alt="Vuetify Name" v-if="settings.screenSize.type.isxs" contain
                            src="../assets/login-mail-track.png" width="100%" max-width="15%" />
                        <v-spacer></v-spacer>
                        <template>
                            <div class="my-5">
                                <v-btn icon @click="toggleDarkMode">
                                    <v-icon>
                                        {{
                                            $vuetify.theme.dark ? 'mdi-white-balance-sunny' :
                                                'mdi-moon-waxing-crescent'
                                        }}
                                    </v-icon>
                                </v-btn>
                            </div>
                        </template>
                    </v-row>
                </div>
                <div class="d-none d-lg-block">
                    <v-row class="mt-2">
                        <v-col md="4" class="text-left pt-0 pb-0">
                            <h3>Mail Tracking Minerba</h3>
                        </v-col>
                        <v-col md="8" class="text-right  pt-0 pb-0">
                            <v-row>
                                <v-spacer></v-spacer>
                                <span class="pr-15" v-text="users.name"></span>
                                <div class="text-center d-flex align-center justify-space-around pr-10">
                                    <v-btn small icon @click="toggleDarkMode">
                                        <v-icon>
                                            {{
                                                $vuetify.theme.dark ? 'mdi-white-balance-sunny' :
                                                    'mdi-moon-waxing-crescent'
                                            }}
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <v-menu transition="slide-x-transition" bottom right offset-y>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn v-bind="attrs" v-on="on" small icon>
                                            <v-icon>mdi-cog-outline</v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-item to="/profile">
                                            <v-icon class="pr-3">mdi-account-cog-outline</v-icon>Profile</v-list-item>
                                        <v-list-item to="/logout"> <v-icon class="pr-3">mdi-logout-variant</v-icon>
                                            Logout</v-list-item>
                                    </v-list>
                                </v-menu>
                            </v-row>
                        </v-col>
                        <v-col md="3">
                            <router-link to="/">
                                <v-img alt="Vuetify Name" class="shrink mt-1 hidden-sm-and-down" contain
                                    src="../assets/login-mail-track.png" width="20%" />
                            </router-link>

                        </v-col>
                        <v-col md="9" class="text-right">
                            <v-btn :small="styleData.small" :rounded="styleData.rounded" v-for="link in renderMenu"
                                :active-class="('white--text ' + settings.color)" :key="link.name" :to="link.path" text
                                class="my-4" :outlined="link.outlined" v-show="link.isShow">

                                <span>
                                    {{ link.name }}
                                </span>
                            </v-btn>
                        </v-col>
                    </v-row>
                    <div class="mt-2">
                        <v-alert text dense close-icon="mdi-close-circle-outline" color="cyan darken-2"
                            v-show="alertSuccessLogin" elevation="2" icon="mdi-information-outline" border="left"
                            dismissible transition="scale-transition">
                            Hi <strong>{{ users.name }}</strong> Welcome to Mail Tracking Minerba
                        </v-alert>
                    </div>
                </div>
            </v-main>
        </v-app-bar>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "Header",
    data() {
        return {
            alertSuccessLogin: false,
            isMobileData: false,
            imageName: "",
            darkMode: false,
            dialog: false,
            menu: {},
            styleData: {
                small: false,
                rounded: true,
                color: "grey darken-2"
            },
            users: []
        }
    },
    created() {
        this.renderImg();
        this.isMobileData = this.$store.state.settings["settings"].isMobileData;
        console.log(this.$store.state.settings["settings"].isMobileData);
        var listData = JSON.parse(localStorage.getItem('userData'));
        this.users = listData != undefined && listData.user ? listData.user : [];
        const lookupData = {
            lookupData: listData.settings,
        }
        this.$store.dispatch('settings', lookupData);
        this.alertSuccessLogin = localStorage.getItem('alertSuccessLogin');
        setTimeout(() => {
            this.alertSuccessLogin = false;
            localStorage.removeItem('alertSuccessLogin');
        }, 5000);


    },
    methods: {
        searching() {
            this.dialog = true;
        },
        toggleDarkMode() {
            this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
            this.darkMode = !this.darkMode;
            this.renderImg(true);
        },
        renderImg(isToggle) {
            if (isToggle) {

                localStorage.setItem('darkMode', this.darkMode);
            }
            this.darkMode = localStorage.getItem('darkMode') == "false" ? false : true;

            if (this.darkMode) {
                this.imageName = this.$store.state.settings['image'].darkmode;
            } else {
                this.imageName = this.$store.state.settings['image'].lightmode;
            }

            this.$vuetify.theme.dark = this.darkMode;
        }
    },
    computed: {
        ...mapState(['settings']),
        switchLabel() {
            return this.darkMode == true ? 'light' : 'dark';
        },
        renderMenu() {
            var level = parseInt(this.users.roleLevel);
            var menus = [
                {
                    name: "Home",
                    path: "/",
                    icon: "mdi-home-roof",
                    isShow: ((level == 99 || level == 0 || level == 1 || level == 2 || level == 3 || level == 4 || level == 5) ? true : false)
                },
                {
                    name: "Inbox",
                    path: "/inbox",
                    icon: "mdi-phone",
                    isShow: ((level == 99 || level == 1 || level == 2 || level == 3 || level == 4 || level == 5) ? true : false)
                },
                {
                    name: "Outbox",
                    path: "/outbox",
                    icon: "mdi-phone",
                    isShow: ((level == 99 || level == 1 || level == 2 || level == 3 || level == 4 || level == 5) ? true : false)
                },
                {
                    name: "Nadine",
                    path: "/nadine",
                    icon: "mdi-phone",
                    isShow: ((level == 99 || level == 0) ? true : false)
                },
                {
                    name: "Employee",
                    path: "/employee",
                    icon: "mdi-phone",
                    isShow: ((level == 99) ? true : false)
                },
                // {
                //     name: "Access",
                //     path: "/access",
                //     icon: "mdi-phone",
                //     isShow: ((level == 99) ? true : false)
                // },
                {
                    name: "Unit",
                    path: "/unit",
                    icon: "mdi-phone",
                    isShow: ((level == 99) ? true : false)
                },
                {
                    name: "Report",
                    path: "/report",
                    icon: "mdi-phone",
                    isShow: ((level == 99) ? true : false)
                },
            ];
            return menus;
        }
    },
    components: {
    },
}
</script> 