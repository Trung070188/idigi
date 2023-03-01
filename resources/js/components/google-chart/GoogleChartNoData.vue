<template>
    <GChart :type="type" :data="data" :options="options"  />
</template>

<script>
    import { GChart } from 'vue-google-charts/legacy';
    export default {
        name: 'GoogleChartNoData',
        props:['ColumnChart','chart_data','chartOptions'],
        components: {
            GChart,
        },
        mounted() {

        },
        data() {
            let chartOptions = {
                chart: {
                    title: 'Company Performance',
                    subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                },
                fontSize:15,
                vAxis:{
                    minValue: 0,
                    maxValue: 60,
                    gridlines: {
                        count: 6,
                    },
                    viewWindow: {
                        min: 0,
                    },
                },
                hAxis: {
                    title: 'No data exists here.',
                    titlePosition: 'out', // Hiển thị tiêu đề dưới trục hoành
                },
                legend: {
                    position: 'none', // Tắt không hiển thị chú thích
                },
                chartArea: {
                    left: '12%',
                    top: '5%',
                    width: '70%',
                    bottom:"20%"
                    // height: '80%',
                },
                // width: 800,
                height: 600,
            };
            let  chartData = [
                ['Year', 'Download app', 'Download lesson'],
                ['Jan', 0, 0],
                ['Feb', 0, 0],
                ['Mar', 0, 0],
                ['Apr', 0,0],
                ['May', 0,0],
                ['Jun', 0,0],
                ['Jul', 0,0],
                ['Aug', 0,0],
                ['Sep', 0,0],
                ['Oct', 0,0],
                ['Nov', 0,0],
                ['Dec', 0,0],
            ];
            return {
                check:0,
                style:[],
                type: "ColumnChart",
                data: chartData,
                options: chartOptions,
            };
        },
    };
</script>
