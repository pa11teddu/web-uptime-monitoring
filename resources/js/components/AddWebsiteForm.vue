<template>
    <div class="mt-4 mb-6 p-4 bg-gray-900 rounded-lg border border-gray-700">
        <h3 class="text-sm font-semibold mb-2 text-gray-200">Monitor New Website</h3>
        <form @submit.prevent="submitNewWebsite" class="flex gap-2">
            <input 
                type="url" 
                v-model="websiteUrl" 
                placeholder="https://example.com" 
                required
                class="flex-1 p-2 border border-gray-600 rounded text-sm bg-gray-800 text-white placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
            />
            <button 
                type="submit" 
                :disabled="isSubmitting"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 disabled:opacity-50 transition-colors"
            >
                {{ isSubmitting ? 'Adding...' : 'Add' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    clientId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['website-added']);

const websiteUrl = ref('');
const isSubmitting = ref(false);

const submitNewWebsite = async () => {
    if (!websiteUrl.value.trim()) return;

    isSubmitting.value = true;
    try {
        const apiResponse = await axios.post('/api/websites', {
            client_id: props.clientId,
            url: websiteUrl.value.trim()
        });
        emit('website-added', apiResponse.data);
        websiteUrl.value = '';
    } catch (error) {
        console.error('Failed to add website:', error);
        alert('Failed to add website. Please check the URL and try again.');
    } finally {
        isSubmitting.value = false;
    }
};
</script>
