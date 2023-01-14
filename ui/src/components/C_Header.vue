 
<template>
    <div>
        <v-app-bar color="primary" elevation="0" height="auto">

            <v-main>
                <div class="d-lg-none">
                    <v-row>
                        <v-img alt="Vuetify Name" v-if="settings.screenSize.type.islg" contain
                            :src="require(`../assets/${imageName}`)" width="100%" max-width="30%" />

                        <v-img alt="Vuetify Name" v-if="settings.screenSize.type.ismd" max-width="30%" contain
                            :src="require(`../assets/${imageName}`)" width="100%" />

                        <v-img alt="Vuetify Name" v-if="settings.screenSize.type.isxs" contain
                            :src="require(`../assets/${imageName}`)" width="100%" max-width="60%" />
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
                                <span class="pr-15">123412342 - Noval Nauw</span>
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
                        <!-- <v-col md="3">
                            <router-link to="/">
                                <v-img alt="Vuetify Name" class="shrink mt-1 hidden-sm-and-down" contain min-width="100"
                                    :src="require(`../assets/${imageName}`)" width="100%" />
                            </router-link>

                        </v-col> -->
                        <v-col md="12" class="text-right">
                            <v-btn :small="styleData.small" :rounded="styleData.rounded" v-for="link in menu.right"
                                :active-class="('white--text ' + settings.color)" :key="link.name" :to="link.path" text
                                class="my-4" :outlined="link.outlined">

                                <v-menu v-if="link.submenu != undefined" open-on-hover bottom offset-y
                                    transition="slide-x-transition">
                                    <template v-slot:activator="{ on, attrs }">
                                        <span v-bind="attrs" v-on="on">
                                            {{ link.name }} <v-icon>{{ link.icon }}</v-icon>
                                        </span>
                                    </template>

                                    <v-list width="150">
                                        <v-list-item v-for="(item, index) in link.submenu" :key="index" link
                                            :to="item.path">
                                            <v-list-item-title>{{ item.name }}</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>

                                <span v-if="link.submenu == undefined && link.name != 'Search'">
                                    {{ link.name }}
                                </span>
                                <span v-if="link.name == 'Search'" @click="searching()">
                                    <v-icon>{{ link.icon }}</v-icon>
                                </span>
                            </v-btn>
                        </v-col>
                    </v-row>
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
            isMobileData: false,
            imageName: "",
            darkMode: false,
            dialog: false,
            menu: {},
            styleData: {
                small: false,
                rounded: true,
                color: "grey darken-2"
            }
        }
    },
    created() {
        this.renderImg();
        this.getMenuData();
        this.isMobileData = this.$store.state.settings["settings"].isMobileData;
        console.log(this.$store.state.settings["settings"].isMobileData);
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
        },
        getMenuData() {
            this.menu = this.$store.state.settings['menu'];
        }
    },
    computed: {
        ...mapState(['settings']),
        switchLabel() {
            return this.darkMode == true ? 'light' : 'dark';
        }
    },
    components: { 
    },
}
</script> 