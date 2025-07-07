<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Contact</h1>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input v-model="form.phone" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    <div v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</div>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <Link href="/contacts" class="text-gray-600 hover:text-gray-800">Cancel</Link>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Save Contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
});

const submit = () => {
    form.clearErrors();

    if (!form.name) {
        form.setError('name', 'Name is required.');
    } else if (form.name.length < 3) {
        form.setError('name', 'Name must be at least 3 characters.');
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.email) {
        form.setError('email', 'Email is required.');
    } else if (!emailRegex.test(form.email)) {
        form.setError('email', 'Email is not valid.');
    }

    const phoneRegex = /^\d+$/;
    if (!form.phone) {
        form.setError('phone', 'Phone is required.');
    } else if (!phoneRegex.test(form.phone)) {
        form.setError('phone', 'Phone must contain only numbers.');
    }

    if (Object.keys(form.errors).length === 0) {
        form.post('/contacts', {
            preserveState: false,
        });
    }
};
</script>
