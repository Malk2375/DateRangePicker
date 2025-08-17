<script setup>
import {ref, computed, watch} from "vue";
import moment from "moment";

const props = defineProps({
    show: Boolean,
    value: Object,
    withDate: Boolean,
    withTime: Boolean,
});

const emit = defineEmits(["change"]);

const currentMonth = ref((props.value || moment()).clone().startOf("month"));

watch(
    () => props.value,
    (val) => {
        if (val) {
            currentMonth.value = val.clone().startOf("month");
        } else {
            currentMonth.value = moment().startOf("month");
        }
    },
    {immediate: true}
);

const daysInMonth = computed(() => {
    const start = currentMonth.value.clone().startOf("week");
    const end = currentMonth.value.clone().endOf("month").endOf("week");
    const days = [];
    const day = start.clone();
    while (day.isBefore(end)) {
        days.push(day.clone());
        day.add(1, "day");
    }
    return days;
});

const isSelected = (day) =>
    props.value && day.isSame(props.value, "day");

const selectDay = (day) => {
    let newDate = props.value ? props.value.clone() : moment();
    newDate.year(day.year()).month(day.month()).date(day.date());
    emit("change", newDate);
};

const changeMonth = (dir) => {
    currentMonth.value = currentMonth.value.clone().add(dir, "month");
};

const updateTime = (type, val) => {
    let newDate = props.value ? props.value.clone() : moment();
    if (type === "hour") newDate.hour(val);
    if (type === "minute") newDate.minute(val);
    emit("change", newDate);
};
</script>

<template>
    <div v-if="show" class="absolute mt-1 z-40 w-1\/2 bg-white border rounded-lg shadow-lg p-3" style="left: 50%; transform: translateX(-50%);">
        <!-- Header -->
        <div class="flex justify-between items-center mb-2">
            <button class="w-5" @click="changeMonth(-1)">‹</button>
            <span>{{ currentMonth.format("MMMM YYYY") }}</span>
            <button class="w-5" @click="changeMonth(1)">›</button>
        </div>

        <!-- Days of week -->
        <div class="grid grid-cols-7 text-xs font-bold text-gray-500 mb-1">
            <div v-for="d in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="d">{{ d }}</div>
        </div>

        <!-- Days -->
        <div class="grid grid-cols-7 gap-1 text-center">
            <div
                v-for="day in daysInMonth"
                :key="day.format('YYYY-MM-DD')"
                class="p-2 rounded cursor-pointer"
                :class="{
                  'bg-indigo-500 text-white': isSelected(day),
                  'text-gray-400': day.month() !== currentMonth.month(),
                  'hover:bg-indigo-200': true
                }"
                @click="selectDay(day)"
            >
                {{ day.date() }}
            </div>
        </div>
    </div>
</template>
