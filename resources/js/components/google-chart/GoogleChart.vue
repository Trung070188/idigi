<template>
    <GChart :type="type" :data="chart_data" :options="options" v-if="chart_data"/>
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
            console.log(this.chart_data)
            let chartOptions = {
                chart: {
                },
                width: 700,
                height: 600,
            };
            return {
                check:0,
                type: "ColumnChart",
                data: [],
                options: chartOptions,
            };
        },
    };
</script>
