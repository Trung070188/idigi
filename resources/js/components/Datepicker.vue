<template>
    <input :id="id" name="time"
           @change="onChange()"
           :placeholder="placeholder"
            autocomplete="off"
            class="form-control daterange1"  >
</template>

<script>
    const DATE_LOCALES = {
        "format": "DD/MM/YYYY",
        "separator": " -- ",
        "applyLabel": "Chọn",
        "cancelLabel": "Hủy",
        "fromLabel": "Từ",
        "toLabel": "tới",
        "customRangeLabel": "Tùy chọn",
        "daysOfWeek": [
            "CN",
            "T2",
            "T3",
            "T4",
            "T5",
            "T6",
            "T7"
        ],
        "monthNames": [
            "Tháng 1",
            "Tháng 2",
            "Tháng 3",
            "Tháng 4",
            "Tháng 5",
            "Tháng 6",
            "Tháng 7",
            "Tháng 8",
            "Tháng 9",
            "Tháng 10",
            "Tháng 11",
            "Tháng 12"
        ],
        "firstDay": 1
    };
    export default {
        name: "Datepicker",
        props: ['value', 'placeholder', 'timepicker', 'mindate', 'id'],

        data: function () {
            return {
            }
        },
        methods: {
            onChange() {
               if (!this.$el.value) {
                   this.$emit('input', null);
               }
            }
        },
        watch: {
           value: function () {
                if (!this.value) {
                    this.$el.value = '';
                }
           }
        },
        mounted: function () {
            var self = this;



            var value =   this.value ? moment( this.value) : moment();
            const format = 'DD/MM/YYYY HH:mm';

            let minDate=  undefined;
            if (this.mindate === 'now') {
                minDate = moment();
            }


            $( this.$el).daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                startDate:  value ,
                endDate:  value,
                locale: DATE_LOCALES,
                timePicker: this.timepicker || false,
                timePicker24Hour: true,
                //minDate

            },  (start, end) => {
                this.$emit('input', start.format('YYYY-MM-DD HH:mm:ss'));
                this.$el.value = start.format(format);
            });

            $(this.$el).on('apply.daterangepicker', function(ev, picker) {
                self.$emit('input', picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
                self.$el.value = picker.endDate.format(format);
            });

           // var _query  = $router.getQuery();
            if (this.value) {
                this.$el.value = value.format(format);
            }

        },
    }
</script>

<style scoped>

</style>
