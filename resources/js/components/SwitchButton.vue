<template>

    <label class="switch-button"><input ref="input" v-model="isChecked" @change="toggle()" type="checkbox"> <span class="slider round"></span></label>
</template>

<script>
    export default {
        name: "SwitchButton",
        data: function () {
            const v = typeof this.value==='boolean' ? this.value : parseInt(this.value);
            return {
                isChecked: !!v
            }
        },
        mounted: function () {
           // new Switchery(this.$el);
        },
        props: ['value', 'change', 'input_id'],
        watch: {
            value: function (v) {
                this.isChecked = v;

            }
        },
        methods: {
            toggle: function() {
                this.$emit('input', this.$refs.input.checked ? 1 : 0 );
                this.$emit('change')
            }
        }
    }
</script>

<style scoped>
    .switch-button {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 30px;
        margin-bottom: 0;
        margin-top:4px
    ;
    }
    input:focus+.slider {
        box-shadow: 0 0 1px #2196f3;
    }

    input:checked+.slider {
        background-color: #2196f3;
    }
    .slider.round {
        border-radius: 34px;
    }
    .switch-button input {
        display: none;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    .slider.round:before {
        border-radius: 50%;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 2px;
        bottom: 3px;
        background-color: #fff;
        -webkit-transition: .4s;
        transition: .4s;
    }

</style>
