<script setup>
import {computed} from "vue";
import Datepicker from "@vuepic/vue-datepicker";
import moment from "moment";

const props = defineProps({
    show: Boolean,
    value: Object,
    withDate: Boolean,
    withTime: Boolean,
});

const emit = defineEmits(["change"]);

const model = computed({
    get: () => props.value?.toDate?.() ?? null,
    set: (val) => emit("change", val ? moment(val) : null),
});
</script>

<template>
    <div v-if="show" class="absolute mt-1 z-50 w-full bg-white">
        <Datepicker
            v-model="model"
            :enable-time-picker="withTime"
            :format="withTime ? 'HH:mm dd/MM/yyyy' : 'dd/MM/yyyy'"
            :auto-apply="true"
            :teleport="false"
            class="z-50"
        />
    </div>
</template>
