<script setup>

import ChatHeader from '@/components/chats/ChatHeader.vue';
import ChatMessages from '@/components/chats/ChatMessages.vue';
import ChatComposer from '@/components/chats/ChatComposer.vue';

const props = defineProps({
  chat: Object,
  currentUserId: Number,
});

const emit = defineEmits(['send-message'])

const onSendMessage = (message) => {
  emit('send-message', message)
}
</script>

<template>
  <div v-if="!props.chat" class="textContent">
    <span>Выберите чат для общения</span>
  </div>
  <div v-else class="chat">
    <ChatHeader :chat="props.chat" />
    <ChatMessages :chat="props.chat" :current-user-id="currentUserId" />
    <ChatComposer @send-message="onSendMessage" />
  </div>
</template>

<style lang="scss" scoped>
.chat {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.textContent {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>