<template>
    <div class="mt-4 p-4 bg-gray-900 rounded border border-gray-700">
        <h4 class="text-xs font-bold uppercase text-gray-400 mb-2">Uptime History (Last 24h)</h4>
        <div class="h-40 relative" v-if="!isLoadingData && graphData">
            <Line :data="graphData" :options="graphConfiguration" />
        </div>
        <p v-else-if="isLoadingData" class="text-xs text-gray-500">Loading stats...</p>
        <p v-else class="text-xs text-gray-500">No data available.</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js';
import axios from 'axios';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

const props = defineProps({
    websiteId: {
        type: Number,
        required: true
    }
});

const isLoadingData = ref(true);
const graphData = ref(null);

const graphConfiguration = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            title: { display: true, text: 'Response Time (s)' }
        },
        x: {
            display: false
        }
    },
    plugins: {
        legend: { display: false }
    }
};

onMounted(async () => {
    try {
        const apiResponse = await axios.get(`/api/websites/${props.websiteId}/stats`);
        const monitoringRecords = apiResponse.data;

        if (monitoringRecords.length > 0) {
            const statusColors = monitoringRecords.map(record => 
                record.status_code === 200 ? '#10b981' : '#ef4444'
            );

            graphData.value = {
                labels: monitoringRecords.map(record => 
                    new Date(record.created_at).toLocaleTimeString()
                ),
                datasets: [
                    {
                        label: 'Response Time (s)',
                        backgroundColor: statusColors,
                        borderColor: '#94a3b8',
                        pointBackgroundColor: statusColors,
                        pointBorderColor: statusColors,
                        data: monitoringRecords.map(record => record.response_time),
                        tension: 0.1,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            };
            
            graphConfiguration.plugins.tooltip = {
                callbacks: {
                    afterLabel: (context) => {
                        const record = monitoringRecords[context.dataIndex];
                        return `Status: ${record.status_code}`;
                    }
                }
            };
        }
    } catch (error) {
        console.error('Failed to load stats:', error);
    } finally {
        isLoadingData.value = false;
    }
});
</script>
