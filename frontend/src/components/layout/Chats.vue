<script setup>

import { ref } from 'vue';
const props = defineProps({
  chat: Object,
});

console.log(props.chat.title);

const emit = defineEmits(['send-message'])
const textMessage = ref('');

function onSendMessage() {
  const message = textMessage.value.trim();
  if (message === '') return;

  emit('send-message', message);
  textMessage.value = '';

  const input = document.querySelector(".chat-composer__input");
  input.style.height = 'auto'
}

const MAX_H = 140;

const autoGrow = (e) => {
  const el = e.target;

  el.style.height = 'auto';
  const next = Math.min(el.scrollHeight, MAX_H);
  el.style.height = next + 'px';
  el.style.overflowY = (el.scrollHeight > MAX_H) ? 'auto' : 'hidden';
};
</script>

<template>

  <div class="chat">
    <div class="chat-header">
      <h1>{{ props.chat.title }}</h1>
    </div>
    <div v-if="props.chat.messages.length === 0" class="textContent">
      <span>Начните общение прямо сейчас!</span>
    </div>
    <div v-else class="chat-messages">
      <ul>
        <li class="message" v-for="message in props.chat.messages" :key="message.id">
          <p>{{ message.text }}</p>
        </li>
      </ul>
    </div>
    <div class="chat-composer">
      <textarea name="" id="" v-model="textMessage" class="chat-composer__input" @input="autoGrow" rows="1"></textarea>
      <button class="spark-btn" @click="onSendMessage">
        O
      </button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.textContent {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chat {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.message {
  width: fit-content;
  padding: var(--space-4) var(--space-6);
  border-radius: 8px;
  background: #1F2B50;
  margin-top: 10px;
  max-width: 100%;
  text-align: justify;
}

.chat-messages {
  flex: 1;
  overflow: auto;
  max-width: 100%;
  overflow-x: hidden;
  padding-bottom: 10px;
  scrollbar-gutter: auto;

  &::-webkit-scrollbar {
    width: 0;
  }
}

.chat-composer {
  width: 100%;
  display: flex;
  gap: var(--space-4);
  background-color: #1F2B50;
  border-radius: 20px;
  padding: var(--space-4);
  overflow: visible;
  width: 100%;
  align-items: flex-end;

  &__input {
    width: 100%;
    background: transparent;
    border: none;
    outline: none;

    resize: none;
    overflow: hidden;
    overflow-y: auto;
    line-height: 20px;
    min-height: 20px;
    max-height: 140px;
    padding: 0 8px;
    border-radius: 16px;

    white-space: pre-wrap;
    word-break: break-word;

    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.22) transparent;

    // scrollbar-gutter: stable;
  }

  &__input::-webkit-scrollbar {
    width: 6px;
  }

  &__input::-webkit-scrollbar-track {
    background: transparent;
  }

  &__input::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.18);
    border-radius: 999px;
    border: 2px solid transparent;
    background-clip: content-box;
  }

  &__input::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.28);
    cursor: pointer
  }
}
</style>