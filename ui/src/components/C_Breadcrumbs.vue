<template>
    <v-breadcrumbs :items="items">
        <template v-slot:item="{ item }">
            <v-breadcrumbs-item :disabled="item.disabled" :to="item.href" exact>
                <span :style="`color: ${settings.color}`" v-if="item.disabled == false">
                    {{ item.text.toUpperCase() }}
                </span>
                <span :style="`color: ${item.color}`" v-else>
                    {{ item.text.toUpperCase() }}
                </span>
            </v-breadcrumbs-item>
        </template>
    </v-breadcrumbs>
</template>

<script>
import { mapState } from "vuex";
export default {
    computed: {
        ...mapState(['settings', 'breadcrumData'])
    },
    data: () => ({
        items: [],
    }),
    methods: {
        getData() {
            this.items = this.$store.state.breadcrumbs['breadcrumData'].items;
        }
    },
    created() {
        this.getData();
    }
}
</script>
 