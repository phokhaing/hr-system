<template>
    <div>
        <v-select :options="getStaff" :reduce="staff => staff.id" label="display_staff_name" style="margin-bottom: 3px"
                  v-model="selectedStaff" :value="$store.selectedStaff"
                  @input="$emit('clickStaff', selectedStaff)" :required="true" id="staff_id">
        </v-select>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';

    export default {
        name: "StaffSelection",
        props: ['options', 'value'],
        data() {
            return {
                selectedStaff: null,
            }
        },
        computed: {
            ...mapGetters([
                'getStaff',
            ]),
        },
        methods: {
            ...mapActions([
                'setStaff'
            ]),
        },
        mounted() {
            this.setStaff();
        },

        //TODO: fix error vue with select2 => https://forum.vuejs.org/t/implementing-select2-from-vuejs-examples/17462/4
        // updated() {
        //     $("#staff_id").select2();
        // }
    }
</script>

<style scoped>

</style>