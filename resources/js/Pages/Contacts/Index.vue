<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Contact List</h1>
                <Link href="/contacts/create"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                        clip-rule="evenodd" />
                </svg>
                New Contact
                </Link>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium">{{
                                                contact.name.charAt(0).toUpperCase() }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ contact.name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ contact.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ contact.phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <Link :href="`/contacts/${contact.id}/edit`"
                                            class="text-indigo-600 hover:text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        </Link>
                                        <button @click="deleteContact(contact.id)"
                                            class="text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="contacts.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No contacts found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium">{{ contacts.from }}</span> to <span class="font-medium">{{
                            contacts.to }}</span> of <span class="font-medium">{{ contacts.total }}</span> contacts
                    </div>
                    <div class="flex space-x-2">
                        <Link v-if="contacts.prev_page_url" :href="contacts.prev_page_url"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Previous
                        </Link>
                        <Link v-if="contacts.next_page_url" :href="contacts.next_page_url"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    contacts: {
        type: Object,
        default: () => ({
            data: [],
            from: 0,
            to: 0,
            total: 0,
            prev_page_url: null,
            next_page_url: null
        })
    }
});

const deleteContact = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will permanently delete this contact.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/contacts/${id}`);
            Swal.fire(
                'Deleted!',
                'The contact has been removed successfully.',
                'success'
            );
        }
    });
};
</script>

<style scoped>
tr {
    transition: background-color 0.2s ease;
}
</style>
