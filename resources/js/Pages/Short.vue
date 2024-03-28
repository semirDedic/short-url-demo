<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const copyingShortLink = ref(false);
const shortUrlInput = ref(null);
const currentUrlInput = ref(null);

const form = useForm({
    url: '',
});

const copyShortLink = () => {
    copyingShortLink.value = true;

    nextTick(() => shortUrlInput.value.focus());
};

const submit = () => {
    form.post(route('generate-hash'), {
        preserveScroll: true,
        onSuccess: () => copyShortLink(),
        onError: () => {
            if (form.errors.url) {
                form.reset('url');
                currentUrlInput.value.focus();
            }
        },
        onFinish: () => form.reset('url'),
    });
};

const closeModal = () => {
    copyingShortLink.value = false;

    form.reset();
};
</script>

<template>
    <GuestLayout>

        <Head title="Generate hash" />

        <div v-if="$page.props.flash.message" class="mb-4 font-medium text-sm text-green-600">
            {{ $page.props.flash.message }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="url" value="Original url" />

                <TextInput id="url" ref="currentUrlInput" type="url" class="mt-1 block w-full" v-model="form.url" required />

                <InputError class="mt-2" :message="form.errors.url" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Generate short url
                </PrimaryButton>
            </div>
        </form>

        <Modal :show="copyingShortLink" @close="closeModal">
            <div class="p-6">

                <h2 class="text-lg font-medium text-gray-900">
                    You have successfully generated a short link
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    You can copy the link below.
                </p>

                <div class="mt-6">

                    <InputLabel for="shortUrl" value="Password" class="sr-only" />

                    <TextInput
                        id="shortUrl"
                        ref="shortUrlInput"
                        v-model="$page.props.flash.shortUrl"
                        type="url"
                        class="mt-1 block w-3/4"
                        placeholder="Short Url"
                        disabled
                    />
                </div>

                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeModal"> Close </PrimaryButton>
                </div>
            </div>
        </Modal>



    </GuestLayout>
</template>
