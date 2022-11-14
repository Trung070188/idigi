<template>
    <GChart :type="type" :data="chart_data" :options="options" v-if="chart_data" />
</template>

<script>
    import { GChart } from 'vue-google-charts/legacy';
    export default {
        name: 'GoogleChart',
        props:['ColumnChart','chart_data','chartOptions'],
        components: {
            GChart,
        },
        mounted() {

        },
        data() {
            let chartOptions = {
                fontSize:15,
                chart: {
                },
                width: 750,
                height: 600,
            };
            return {
                check:0,
                style:[],
                type: "ColumnChart",
                data: [],
                options: chartOptions,
            };
        },
    };
</script>
