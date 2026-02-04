<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white p-6">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Hero Section -->
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
                    Uptime Monitor
                </h1>
                <p class="mt-4 text-lg text-gray-400">
                    Select a client to view their monitored websites.
                </p>
            </div>

            <!-- Client Selector -->
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 shadow-2xl border border-white/20">
                <label for="client-select" class="block text-sm font-medium text-gray-300 mb-2">Select Client</label>
                <select 
                    id="client-select" 
                    v-model="currentClient" 
                    class="block w-full px-3 py-3 border border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-800 text-white sm:text-sm"
                >
                    <option :value="null" disabled>-- Choose a Client --</option>
                    <option v-for="client in clientList" :key="client.id" :value="client">
                        {{ client.email }}
                    </option>
                </select>
            </div>

            <!-- Dashboard / Website List -->
            <div v-if="currentClient" class="transition-all duration-500 ease-in-out">
                <WebsiteList 
                    :client="currentClient"
                    @visit-website="onWebsiteVisitRequest"
                />
            </div>
            
            <!-- Footer Features -->
            <div v-if="!currentClient" class="grid grid-cols-3 gap-4 text-xs text-gray-400 pt-8 border-t border-gray-800 text-center">
                <div>
                    <span class="block font-semibold text-gray-200">Real-time</span>
                    <span>15-min Checks</span>
                </div>
                <div>
                    <span class="block font-semibold text-gray-200">Instant Alert</span>
                    <span>Email Notifications</span>
                </div>
                <div>
                    <span class="block font-semibold text-gray-200">History</span>
                    <span>24h Uptime Logs</span>
                </div>
            </div>
        </div>

        <!-- Visit Confirmation Modal -->
        <VisitConfirmationModal 
            :visible="isModalVisible"
            :url="targetWebsiteUrl"
            @confirm="proceedToWebsite"
            @cancel="dismissModal"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import WebsiteList from './WebsiteList.vue';
import VisitConfirmationModal from './VisitConfirmationModal.vue';

const clientList = ref([]);
const currentClient = ref(null);

const isModalVisible = ref(false);
const targetWebsiteUrl = ref('');

const loadClients = async () => {
    try {
        const apiResponse = await axios.get('/api/clients');
        clientList.value = apiResponse.data;
    } catch (error) {
        console.error('Failed to fetch clients:', error);
    }
};

const onWebsiteVisitRequest = (website) => {
    targetWebsiteUrl.value = website.url;
    isModalVisible.value = true;
};

const proceedToWebsite = () => {
    window.open(targetWebsiteUrl.value, '_blank');
    dismissModal();
};

const dismissModal = () => {
    isModalVisible.value = false;
    targetWebsiteUrl.value = '';
};

onMounted(loadClients);
</script>
