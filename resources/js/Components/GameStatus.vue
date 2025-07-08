<template>
  <span
    class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full"
    :class="badgeClass"
  >
    {{ label }}
  </span>
</template>

<script setup>
const props = defineProps({
  status: {
    type: [Number, String, Object],
    required: true,
  },
});

const statusMap = {
  1: { label: 'AGUARDANDO', class: 'bg-yellow-100 text-yellow-800' },
  2: { label: 'EM ANDAMENTO', class: 'bg-green-100 text-green-800' },
  5: { label: 'FINALIZADO', class: 'bg-red-100 text-red-800' },
};

const resolveStatusId = () => {
  if (typeof props.status === 'object' && props.status?.id) return props.status.id;
  if (typeof props.status === 'number') return props.status;
  return null;
};

const statusId = resolveStatusId();

const label = statusMap[statusId]?.label ?? 'Desconhecido';
const badgeClass = statusMap[statusId]?.class ?? 'bg-gray-100 text-gray-700';
</script>
