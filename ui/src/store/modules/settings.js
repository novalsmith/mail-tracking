// modules/settings.js
import {
    computed,
    onMounted,
    onUnmounted,
    ref
} from "vue"

export const useBreakpoints = () => {
    let windowWidth = ref(window.innerWidth)

    const onWidthChange = () => windowWidth.value = window.innerWidth
    onMounted(() => window.addEventListener('resize', onWidthChange))
    onUnmounted(() => window.removeEventListener('resize', onWidthChange))
    var listData = {
        xs: false,
        md: false,
        lg: false
    };
    const type = computed(() => {
        if (windowWidth.value < 550) {
            listData.isxs = true;
        } else if (windowWidth.value >= 550 && windowWidth.value < 1265) {
            listData.ismd = true;
        } else if (windowWidth.value >= 1265) {
            listData.islg = true;
        }
        return listData; // This is an unreachable line, simply to keep eslint happy.
    })

    const width = computed(() => windowWidth.value)

    return {
        width,
        type
    }
}

const {
    width,
    type
} = useBreakpoints();


const state = {
    color: "teal",
    isMobileData: null,
    isDarkMode: false,
    outlinedGeneralButton: true,
    defaultImageContentHeight: "300",
    defaultImageContentWidth: "300",
    defaultImageSmallContentHeight: "120",
    allCategory: "semua-kategori",
    image: {
        darkmode: "logoDark.png",
        lightmode: "logoLight.png"
    },
    menu: {
        topLeft: [{
            name: "Jadwal Ibadah",
            path: "/jadwal",
            icon: "mdi-book-open-variant",
            outlined: false
        }, ],
        topRight: [{
                name: "",
                path: "/facebook",
                icon: "mdi-facebook",
                outlined: false
            },
            {
                name: "",
                path: "/instagram",
                icon: "mdi-instagram",
                outlined: false
            },
            {
                name: "",
                path: "/whatsapp",
                icon: "mdi-whatsapp",
                outlined: false
            },
            {
                name: "",
                path: "/youtube",
                icon: "mdi-youtube",
                outlined: false
            }
        ],
        top: [{
            name: "Beranda",
            path: "/",
            icon: "mdi-home-roof"
        }, ],
        right: [{
                name: "Beranda",
                path: "/",
                icon: "mdi-home-roof"
            },
            {
                name: "Profil",
                path: "/profil",
                icon: "mdi-home-city-outline"
            },
            {
                name: "Agenda",
                path: "/agenda",
                icon: "mdi-calendar-multiselect-outline"
            },
            {
                name: "Informasi",
                path: "/informasi",
                icon: "mdi-newspaper-variant-multiple-outline"
            },
            {
                name: "Dokumen",
                path: "/dokumen",
                icon: "mdi-clipboard-file-outline",
                outlined: false
            },
            {
                name: "Media",
                path: "",
                icon: "mdi-chevron-down",
                icon2: "mdi-camera",
                submenu: [{
                        name: "Foto",
                        path: "/media/foto",
                        icon: "mdi-image"
                    },
                    {
                        name: "Video",
                        path: "/media/video",
                        icon: "mdi-video"
                    }
                ],
                outlined: false
            },
            {
                name: "Kontak",
                path: "/kontak",
                icon: "mdi-phone"
            }
        ]
    },
    dialogData: {
        isShowDialog: false,
        isScroll: false,
        isPersistent: false,
        width: 600,
        title: "GKI Lahai-Roi Ayamaru",
        buttonFooter: {
            save: "OK",
            close: "OK"
        },
        modalType: {
            isGalery: false,
            isSearch: false
        },
        data: null
    },
    screenSize: {
        width,
        type
    }
}
export default {
    state,
    mutations: {
        settings(state, settings) {
            state.settings = settings;
        }
    },
    actions: {
        settings(context, settings) {
            context.commit('settings', settings);
        }
    },
    getters: {
        settings: (state) => {
            return state.settings;
        }
    },
    modules: {

    }
}