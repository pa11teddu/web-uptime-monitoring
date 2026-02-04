<template>
    <div class="bg-gray-800 shadow rounded-lg p-6 border border-gray-700">
        <h2 class="text-xl font-semibold mb-4 text-white">Websites for {{ clientData.email }}</h2>
        
        <AddWebsiteForm :client-id="clientData.id" @website-added="onWebsiteAdded" />
        
        <div class="mt-6 border-t border-gray-700 pt-4">
            <h3 class="text-lg font-medium mb-3 text-gray-200">Monitored Websites</h3>
            
            <p v-if="isLoading" class="text-gray-400">Loading websites...</p>
            
            <ul v-else-if="siteList.length > 0" class="space-y-4">
                <li v-for="site in siteList" :key="site.id" class="border border-gray-700 rounded p-3 bg-gray-900/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <!-- Clickable URL -->
                            <a 
                                href="#" 
                                @click.prevent="$emit('visit-website', site)"
                                class="font-medium text-blue-400 hover:text-blue-300 hover:underline"
                            >
                                {{ site.url }}
                            </a>
                            
                            <!-- Status Badge -->
                            <span 
                                v-if="site.latest_log"
                                :class="[
                                    'px-2 py-0.5 rounded text-xs font-bold uppercase',
                                    site.latest_log.status_code === 200 
                                        ? 'bg-green-900/50 text-green-300 border border-green-700' 
                                        : 'bg-red-900/50 text-red-300 border border-red-700'
                                ]"
                            >
                                {{ site.latest_log.status_code === 200 ? 'UP' : 'DOWN' }}
                            </span>
                            <span v-else class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-gray-700 text-gray-300 border border-gray-600">
                                PENDING
                            </span>
                        </div>
                        <div class="flex gap-2 items-center">
                            <button 
                                @click="toggleStatistics(site.id)"
                                class="text-sm text-blue-400 hover:text-blue-300 underline"
                            >
                                {{ activeStatsSiteId === site.id ? 'Hide Stats' : 'Show Stats' }}
                            </button>
                            <button 
                                @click.prevent="$emit('visit-website', site)"
                                class="text-sm text-green-400 hover:text-green-300 underline ml-2"
                            >
                                Visit
                            </button>
                            <button 
                                @click="removeWebsite(site)"
                                class="text-sm text-red-400 hover:text-red-300 underline ml-2"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                    
                    <div v-if="activeStatsSiteId === site.id" class="mt-3 border-t border-gray-700 pt-2">
                        <UptimeGraph :website-id="site.id" />
                    </div>
                </li>
            </ul>
            
            <p v-else class="text-gray-400 italic">No websites found for this client.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';
import AddWebsiteForm from './AddWebsiteForm.vue';
import UptimeGraph from './UptimeGraph.vue';

const props = defineProps({
    client: {
        type: Object,
        required: true
    }
});

defineEmits(['visit-website']);

const siteList = ref([]);
const isLoading = ref(false);
const activeStatsSiteId = ref(null);

const clientData = computed(() => props.client);

const loadWebsites = async () => {
    if (!props.client) return;
    
    isLoading.value = true;
    try {
        const apiResponse = await axios.get(`/api/clients/${props.client.id}/websites`);
        siteList.value = apiResponse.data;
    } catch (error) {
        console.error('Failed to fetch websites:', error);
        alert('Failed to load websites.');
    } finally {
        isLoading.value = false;
    }
};

const onWebsiteAdded = (addedSite) => {
    siteList.value.push(addedSite);
};

const removeWebsite = async (siteToRemove) => {
    if (!confirm(`Are you sure you want to delete ${siteToRemove.url}?`)) {
        return;
    }

    try {
        await axios.delete(`/api/websites/${siteToRemove.id}`);
        siteList.value = siteList.value.filter(site => site.id !== siteToRemove.id);
        if (activeStatsSiteId.value === siteToRemove.id) {
            activeStatsSiteId.value = null;
        }
    } catch (error) {
        console.error('Failed to delete website:', error);
        alert('Failed to delete website.');
    }
};

const toggleStatistics = (siteId) => {
    if (activeStatsSiteId.value === siteId) {
        activeStatsSiteId.value = null;
    } else {
        activeStatsSiteId.value = siteId;
    }
};

onMounted(loadWebsites);

watch(() => props.client, loadWebsites);
</script>
