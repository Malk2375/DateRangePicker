<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Dialog from "@/Components/Common/DialogModal";
import Button from "@/Components/Common/Button";
import Input from "@/Components/Common/Input";
import moment from "moment";
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const emit = defineEmits(["close"]);

const props = defineProps({
    itemToEdit: {
        type: Object,
        default: null,
    },
});

const show = ref(false);
const editing = ref(false);

const form = useForm({
    title: "",
    starts_at: null,
    ends_at: null,
});

// Called when the user clicks on the "Add new" button
const onAddNew = () => {
    form.reset();
    show.value = true;
    editing.value = false;
};
watch(
    () => props.itemToEdit,
    (value) => {
        if (value) {
            editing.value = true;
            form.title = value.title;
            form.starts_at = moment(value.starts_at);
            form.ends_at = moment(value.ends_at);
            show.value = true;
        }
    }
);
// Called when the user submits the form
const onSubmit = () => {
    const transform = (data) => ({
        ...data,
        starts_at: moment(data.starts_at).format("YYYY-MM-DD HH:mm"),
        ends_at: moment(data.ends_at).format("YYYY-MM-DD HH:mm"),
    });


    const requestParams = {
        preserveScroll: true,
        onSuccess: onClose,
    };

    // Stores or updates the item
    if (editing.value) {
        form.transform(transform).put(
            route("events.update", props.itemToEdit.id),
            requestParams,
        );
    } else {
        form.transform(transform).post(route("events.store"), requestParams);
    }
};

// Called when the dialog closes
const onClose = () => {
    form.reset();
    show.value = false;
    emit("close");
};
</script>

<template>
    <div>
        <Button @click="onAddNew">
            <vue-feather type="plus" />
            <span class="ml-2">Add new</span>
        </Button>
        <Dialog :show="show" @close="onClose">
            <template #header>{{
                editing ? "Edit event" : "Add new event"
            }}</template>

            <Input
                name="title"
                label="Title"
                v-model="form.title"
                class="mb-6"
            />
            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">
                Start Date
            </label>
            <Datepicker
                v-model="form.starts_at"
                :enable-time-picker="true"
                :format="'yyyy-MM-dd HH:mm'"
                :minute-increment="5"
                label="Start Date"
                class="mb-6"
            />

            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">
                End Date
            </label>
            <Datepicker
                v-model="form.ends_at"
                :enable-time-picker="true"
                :format="'yyyy-MM-dd HH:mm'"
                :minute-increment="5"
                label="End Date"
                class="mb-6"
            />

            <template #footer>
                <Button variant="secondary" class="mr-3" @click="onClose"
                    >Cancel</Button
                >
                <Button @click="onSubmit">Submit</Button>
            </template>
        </Dialog>
    </div>
</template>

<style scoped></style>
