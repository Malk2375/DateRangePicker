<script setup>
import {computed, ref, onMounted, onBeforeUnmount} from "vue";
import moment from "moment";
import Calendar from "./Partials/CalendarPopup";

defineEmits(["update:modelValue"]);

const props = defineProps({
    modelValue: {
        type: Object,
        validator: moment.isMoment,
    },
    label: String,
    type: {
        type: String,
        validator: (val) => ["date", "datetime", "time"].includes(val),
        default: "datetime",
    },
});

const showPopup = ref(false);
const pickerRef = ref(null);

const hasTime = computed(() => props.type !== "date");
const hasDate = computed(() => props.type !== "time");

const format = computed(() => {
    switch (props.type) {
        case "time":
            return "HH:mm";
        case "date":
            return "DD/MM/YYYY";
        default:
            return "HH:mm DD/MM/YYYY";
    }
});

const handleClickOutside = (e) => {
    if (pickerRef.value && !pickerRef.value.contains(e.target)) {
        showPopup.value = false;
    }
};

onMounted(() => document.addEventListener("mousedown", handleClickOutside));
onBeforeUnmount(() => document.removeEventListener("mousedown", handleClickOutside));
</script>

<template>
    <div ref="pickerRef" class="relative w-full">
        <label v-if="label" class="block font-medium text-sm text-gray-700 mb-1">{{ label }}</label>

        <div
            class="px-3 h-12 w-full border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-left flex items-center justify-between cursor-pointer"
            @click="showPopup = !showPopup"
        >
            <span>{{ modelValue?.format(format) || 'Select a date' }}</span>
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <Calendar
            :show="showPopup"
            :value="modelValue"
            :with-date="hasDate"
            :with-time="hasTime"
            @change="$emit('update:modelValue', $event)"
        />
    </div>
</template>

<style lang="postcss" scoped>
</style>
