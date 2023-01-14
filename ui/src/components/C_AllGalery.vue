<template>
    <div v-if="settings.screenSize.type.islg">
        <v-row class="mb-5">

            <v-col cols="2">
                <h4>Kategori</h4>
            </v-col>
            <v-col cols="10">
                <C_CategorySection @getCategoryBySlug="getCategoryBySlug" />
            </v-col>
        </v-row>


        <v-row>
            <v-col md="4" v-show="isShowgalery" v-for="image in galeryData">
                <v-flex xs12>
                    <v-hover v-slot="{ hover }" open-delay="200">
                        <a href="javascript:void(0)" @click="openImage(image)">
                            <v-card :elevation="hover ? 5 : 1" :class="{ 'on-hover': hover }">
                                <v-container fluid grid-list-lg>
                                    <v-layout row>
                                        <v-img :height="settings.defaultImageSmallContentHeight"
                                            :src="require(`../assets/${image.source}`)" :alt="image.alt"
                                            :lazy-src="require(`../assets/${image.source}`)"
                                            class="grey darken-4 rounded-lg"
                                            :width="settings.defaultImageSmallContentWidth">
                                            <template v-slot:placeholder>
                                                <v-row class="fill-height ma-0" align="center" justify="center">
                                                    <v-progress-circular indeterminate color="grey lighten-5">
                                                    </v-progress-circular>
                                                </v-row>
                                            </template>
                                        </v-img>
                                        <div>
                                            <div class="subheading font-weight-medium mt-3 ml-3">
                                                {{ image.title }}
                                            </div>
                                            <h5 class="float-left font-weight-regular my-2">
                                                <v-chip class="ma-2" small outlined :color="settings.color">
                                                    {{ image.category }}
                                                </v-chip> {{ image.date }}
                                            </h5>
                                        </div>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </a>
                    </v-hover>
                </v-flex>
            </v-col>
            <v-col md="12" v-show="isShowgalery == false">
                <v-alert color="blue-grey" dense outlined icon="mdi-information-outline">
                    Sementara belum ada data
                </v-alert>
            </v-col>

        </v-row>


        <GeneralDialog v-if="this.$store.state.settings['dialogData']['modalType'].isGalery">
            <component :is="child_component"></component>
        </GeneralDialog>

    </div>

</template>

<script>
import { mapState } from "vuex";
import Breadcrumbs from '@/components/C_Breadcrumbs.vue';
import C_CategorySection from '@/components/C_CategorySection.vue';
import GeneralDialog from "@/components/C_GeneralDialog.vue";
import GaleryModal from "@/components/C_GaleryModal.vue";


export default {
    data: () => ({
        dialogm1: '',
        dialog: false,
        isShowgalery: false,
        selectedItem: 0,
        localData: null,
        child_component: 'GaleryModal',
        datafiltering: [],
        isCategoryClicked: false,
        items: [
            { text: 'Natal', icon: 'mdi-clock', total: 5, slug: "natal" },
            { text: 'Jemaat', icon: 'mdi-account', total: 10, slug: "jemaat" },
            { text: 'PESPARAWI', icon: 'mdi-flag', total: 10, slug: "pesparawi" },
            { text: 'PW', icon: 'mdi-flag', total: 12, slug: "pw" },
            { text: 'Sidang Klasis', icon: 'mdi-flag', total: 3, slug: "sidang-klasis" },
            { text: 'Oukumene', icon: 'mdi-flag', total: 7, slug: "oukumene" },
        ],
        imagesList: [
            {
                source: "lahai1.jpeg", category: "Natal", title: "Natal", total: 15, date: "25 Sept 2022", slugTitle: "natal",
                slugCategory: "natal"
            },
            {
                source: "lahai2.jpeg", category: "Natal", title: "Sidang Klasis 2022", total: 23, date: "15 Sept 2022",
                slugCategory: "natal",
                slugTitle: "sidang-klasis"
            },
            {
                source: "lahai3.jpeg", category: "Oukumene",
                slugCategory: "oukumene",
                title: "Natal", total: 50, date: "6 Agus 2022", slugTitle: "natal"
            },
            {
                source: "lahai4.jpeg", category: "PKB", title: "Ibadah PKB",
                slugCategory: "pkb",
                total: 14, date: "25 Des 2022", slugTitle: "pkb"
            },
            {
                source: "lahai5.jpeg", category: "Oukumene", title: "Sidang Klasis",
                slugCategory: "oukumene", total: 20, date: "2 Sept 2022", slugTitle: "pw"
            },
            {
                source: "lahai6.jpeg", category: "Pesparawi", title: "Pesparawi",
                slugCategory: "pesparawi", total: 15, date: "8 Sept 2022", slugTitle: "pesparawi"
            }
        ]
    }),
    components: {
        Breadcrumbs,
        C_CategorySection,
        GaleryModal,
        GeneralDialog,

    },
    computed: {
        ...mapState(['settings']),
        galeryData() {
            if (!this.isCategoryClicked) {
                this.datafiltering = this.imagesList;
            }
            return this.datafiltering;
        }
    },
    mounted() {
        this.localData = this.$store.state.settings['dialogData'];
        this.isShowgalery = this.imagesList.length > 0 ? true : false;
    },
    methods: {
        openImage(image) {
            this.dialog = true;
            this.localData.isShowDialog = true;
            this.localData.modalType.isGalery = true;
            this.localData.modalType.isSearch = false;
            this.localData.data = image;
            const modalData = {
                dialogData: this.localData
            }
            this.$store.dispatch('settings', modalData);
        },
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
                        text: 'Media',
                        disabled: false,
                        href: '/media/foto',
                        color: "#42A5F5"
                    },
                    {
                        text: 'Foto',
                        disabled: true,
                        href: 'breadcrumbs_link_2',
                        color: "#BDBDBD"
                    },
                ],

            }
            this.$store.dispatch('breadcrumData', datas);
        },
        getCategoryBySlug(event) {
            var filteredList = [];
            if (event != '') {
                if (event === this.$store.state.settings.allCategory) {
                    filteredList = this.imagesList;
                } else {
                    filteredList = this.imagesList.filter((e) => e.slugCategory === event).map((e) => { return e });
                }
                this.isCategoryClicked = true;
                if (filteredList.length == 0) {
                    this.isShowgalery = false;
                } else {
                    this.isShowgalery = true;
                }
            }
            this.datafiltering = filteredList;
        }
    },
    created() {
        this.setBreadcrumsData();
        let indexData = 0;
        this.items.forEach((el, index) => {
            if (el.slug === this.$route.params.id) indexData = index;
        });
        this.selectedItem = indexData;
    }
}
</script>