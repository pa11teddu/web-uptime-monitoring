<template>
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-70 overflow-y-auto h-full w-full flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="relative p-5 border border-gray-700 w-96 shadow-2xl rounded-md bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-white">Wait a moment!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-gray-300">
                        You are about to visit <strong class="text-blue-400">{{ url }}</strong>.
                    </p>
                    <p class="text-gray-400 mt-2 text-sm">
                        Do you want to continue?
                    </p>
                </div>
                <div class="items-center px-4 py-3 space-y-3">
                    <button 
                        @click="$emit('confirm')"
                        class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                    >
                        Continue
                    </button>
                    <button 
                        @click="$emit('cancel')"
                        class="px-4 py-2 bg-gray-700 text-gray-300 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    visible: {
        type: Boolean,
        default: false
    },
    url: {
        type: String,
        required: true
    }
});

defineEmits(['confirm', 'cancel']);
</script>
