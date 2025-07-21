<script setup>

import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const page = usePage();
const flash = ref(page.props.flash);

onMounted(() => {
    if (flash.value.error) {
        toast.error(flash.value.error);
    }
    if (flash.value.success) {
        toast.success(flash.value.success);
    }
    if (flash.value.alert) {
        toast.warning(flash.value.alert);
    }
    if (flash.value.info) {
        toast.info(flash.value.info);
    }
    flash.value = null; // Clear flash after displaying
});

</script>