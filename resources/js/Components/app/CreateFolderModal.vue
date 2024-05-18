<template>
    <Modal :show="modelValue" @show="onShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Create New Folder
            </h2>

            <div class="mt-6">
                <InputLabel for="folderName" value="Folder Name" class="sr-only"/>

                <TextInput
                    ref="folderNameInput"
                    type="text" id="folderName"
                    class="mt-1 block w-full"
                    placeholder="Folder Name"
                    v-model="form.name"
                    @keyup.enter="createFolder"
                    :class="form.errors.name ? 'border-red-500 focus:border-red-500 focus:ring-red-500': '' "
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <PrimaryButton class="ml-3" :class="{'opacity-25': form.processing}" @click="createFolder" :disable="form.processing">Submit</PrimaryButton>
            </div>
        </div>

    </Modal>
</template>

<script setup>
    import Modal from "@/Components/Modal.vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import TextInput from "@/Components/TextInput.vue";
    import InputError from "@/Components/InputError.vue";
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import {useForm} from "@inertiajs/vue3";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import {nextTick, ref} from "vue";

    const form = useForm({
        name: ''
    });

    const folderNameInput = ref(null)

    const {modelValue} = defineProps({
        modelValue: Boolean
    });

    const emit = defineEmits(['update:modelValue']);
    function createFolder() {
        form.post(route('folder.create'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                form.reset();
            },
            onError: () => {
                folderNameInput.value.focus();
            }
        })
    }

    function closeModal() {
        emit('update:modelValue');
        form.clearErrors();
        form.reset();
    }

    function onShow() {
        nextTick( () => {
            folderNameInput.value.focus();
        });
    }
</script>

<style scoped>

</style>
