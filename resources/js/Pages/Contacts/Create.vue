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
import Swal from 'sweetalert2';

const form = useForm({
    name: '',
    email: '',
    phone: '',
});

const validateForm = () => {
    form.clearErrors();
    let isValid = true;

    if (!form.name) {
        form.setError('name', 'Name is required.');
        isValid = false;
    } else if (form.name.length < 3) {
        form.setError('name', 'Name must be at least 3 characters.');
        isValid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.email) {
        form.setError('email', 'Email is required.');
        isValid = false;
    } else if (!emailRegex.test(form.email)) {
        form.setError('email', 'Email is not valid.');
        isValid = false;
    }

    const phoneRegex = /^\d+$/;
    if (!form.phone) {
        form.setError('phone', 'Phone is required.');
        isValid = false;
    } else if (!phoneRegex.test(form.phone)) {
        form.setError('phone', 'Phone must contain only numbers.');
        isValid = false;
    }

    return isValid;
};

const submit = () => {
    if (!validateForm()) return;

    form.post(route('contacts.store'), {
        preserveScroll: true,
        onError: (errors) => {
            if (errors.email === 'duplicate_email') {
                showDuplicateEmailAlert(errors.existingContact);
            } else {
                // Mostrar outros erros normalmente
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please check the form for errors.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'Contact created successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    });
};

const showDuplicateEmailAlert = (existingContact) => {
    Swal.fire({
        title: 'Duplicate Email Detected',
        html: `The email <strong>${form.email}</strong> is already in use by:<br><br>
               <div style="text-align: left; margin-left: 20px;">
                 <p><strong>Name:</strong> ${existingContact.name}</p>
                 <p><strong>Phone:</strong> ${existingContact.phone}</p>
               </div><br>
               Would you like to save anyway?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, save anyway',
        cancelButtonText: 'No, cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        focusCancel: true,
        customClass: {
            popup: '!rounded-lg',
            htmlContainer: '!text-left'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Força o submit ignorando o erro de duplicação
            form.post(route('contacts.store'), {
                preserveScroll: true,
                onFinish: () => form.reset(),
                onError: (errors) => {
                    if (errors.email && errors.email !== 'duplicate_email') {
                        Swal.fire({
                            title: 'Error',
                            text: errors.email,
                            icon: 'error'
                        });
                    }
                }
            });
        }
    });
};
</script>