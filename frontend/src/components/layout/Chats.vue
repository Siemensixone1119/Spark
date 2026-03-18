<script setup>

import { ref, computed } from 'vue';
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

const groupedMessages = computed(() => {
  if (!props.chat?.messages?.length) return [];
  const groups = [];

  props.chat.messages.forEach(message => {
    const lastGroup = groups[groups.length - 1];

    if (!lastGroup || message.author !== lastGroup.author) {
      groups.push({
        author: message.author,
        messages: [message]
      })
      return
    }
    lastGroup.messages.push(message);
  });
  return groups;
})
</script>
<template>
  <div class="chat">
    <div class="chat-header">
      <h1 class="chat-header__title">{{ props.chat.title }}</h1>
    </div>
    <div v-if="props.chat.messages.length === 0" class="textContent">
      <span>Начните общение прямо сейчас!</span>
    </div>
    <div v-else class="chat-messages">
      <ul class="message-groups">
        <li class="message-group" v-for="group in groupedMessages" :key="group.messages[0].id" :class="{ 'message-group--me': group.author === 'me' }">
          <div class="message-group__avatar" :style="{ backgroundImage: `url(${props.chat.avatarUrl})` }"></div>
          <div class="message-group__body">
            <ul class="message-group__list">
              <li class="message-group__item" v-for="message in group.messages" :key="message.id">
                <div class="message-bubble">
                  <span class="message-bubble__text">{{ message.text }}</span>
                  <svg width="9" height="17" class="svg-appendix">
                    <g>
                      <path
                        d="M3 17h6V0c-.193 2.84-.876 5.767-2.05 8.782-.904 2.325-2.446 4.485-4.625 6.48A1 1 0 003 17z">
                      </path>
                    </g>
                  </svg>
                </div>
              </li>
            </ul>
          </div>
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
.message-bubble {
  width: fit-content;
  padding: var(--space-4) var(--space-6);
  border-radius: 18px;
  background: #1F2B50;
  max-width: 90%;
  position: relative;
  border-bottom-left-radius: 0;
  max-width: 60%;
  line-height: 1.25;

  &__text {
    word-break: break-word;
  }
}

.message-group {
  &__body {
    width: 100%;
  }

  &__avatar {
    min-width: 36px;
    min-height: 36px;
    border-radius: 50%;
    background-size: contain;
  }

  &__list {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  &__item:not(:last-child) {
    .message-bubble {
      border-bottom-left-radius: 8px;
    }

    .svg-appendix {
      display: none;
    }
  }
}

.message-group {
  display: flex;
  flex-direction: row;
  align-items: flex-end;
  gap: 15px;
  margin-top: 12px;

  .svg-appendix {
    fill: #1F2B50;
    position: absolute;
    bottom: 0;
    left: -9px;
    display: block;
    transform: scaleX(1);
  }
}

.message-group--me {
  flex-direction: row-reverse;
  justify-content: flex-end;

  .message-bubble {
    margin-left: auto;
    border-bottom-left-radius: 18px;
    border-bottom-right-radius: 0;
    background: #7b7bde6b;

    .svg-appendix {
      right: -9px;
      left: unset;
      transform: scaleX(-1);
      fill: #7b7bde6b;
    }
  }

  .message-group__item:not(:last-child) {
    .message-bubble {
      border-bottom-right-radius: 8px;
      border-bottom-left-radius: 18px;
    }

    .svg-appendix {
      display: none;
    }
  }
}

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

.chat-header {
  margin: -15px;
  padding: 15px;
  margin-bottom: 0;

  &__title {
    font-weight: 600;
    font-size: 16px;
  }
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