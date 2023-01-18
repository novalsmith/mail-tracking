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
    color: "cyan darken-2",
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
    menu: [{
            name: "Home",
            path: "/",
            icon: "mdi-home-roof"
        },
        {
            name: "Transactions",
            path: "/inboxs",
            icon: "mdi-phone"
        },
        {
            name: "Inbox",
            path: "/inbox",
            icon: "mdi-phone"
        },
        {
            name: "Outbox",
            path: "/outbox",
            icon: "mdi-phone"
        },
        {
            name: "Report",
            path: "/report",
            icon: "mdi-phone"
        }
    ],
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
    },
    users: {
        data: 0
    },
    lookupData: {}
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