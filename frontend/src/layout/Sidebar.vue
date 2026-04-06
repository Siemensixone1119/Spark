<script setup>

import { Check, CheckCheck } from '@lucide/vue';

const props = defineProps(
  {
    chats: Array,
    activeChatId: Number
  }
)
const emit = defineEmits(['select-chat']);

const selectChat = (id) => {
  if (props.activeChatId === id) {
    return
  };

  emit('select-chat', id)
}

function computeDate(unixSeconds) {
  if (!unixSeconds) return '';


  const date = new Date(unixSeconds * 1000);
  const now = Date.now();
  const delta = now - date.getTime();
  const day = 86400000;
  const week = day * 7;

  if (delta < day) {
    return date.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
  }
  if (delta < week) {
    return date.toLocaleDateString('ru-RU', { weekday: 'short' });
  }
  return date.toLocaleDateString('ru-RU');
}
</script>

<template>
  <div v-if="props.chats.length === 0" class="textContent">У вас еще нет чатов</div>
  <div v-else>
    <ul class="chats-list">
      <li v-for="chat in props.chats" :key="chat.id" class="chats-list__item"
        :class="{ active: chat.id === props.activeChatId }">
        <div class="chat-item" @click="selectChat(chat.id)">
          <div class="chat-item__avatar" :style="{ backgroundImage: `url(${chat.avatarUrl})` }">
          </div>
          <div class="chat-item__body">
            <h3 class="chat-item__title">{{ chat.title }}</h3>
            <span class="chat-item__last">{{ chat.lastMessage?.text }}</span>
          </div>
          <div class="chat-item__meta">
            <div class="message-meta">
              <div v-if="!chat.unread" class="message-meta-read">
                <span v-if="chat.lastMessage?.readAt">
                  <CheckCheck />
                </span>
                <span v-else>
                  <Check />
                </span>
              </div>
              <div class="message-meta-time">{{ computeDate(chat.updatedAt) }}</div>
            </div>
            <div class="chat-item__pins">
              <div v-if="chat.unread" class="chat-item__pin">
                <span class="chat-item__pin-cont">{{ chat.unread }}</span>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<style lang="scss" scoped>
.active {
  background-color: #263a62;
}

.chats-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  overflow-y: auto;
  padding: 8px;
  padding-right: 5px;
  gap: 3px;
}

.chats-list__item {
  width: auto;
  padding: 10px var(--space-6);
  // margin: var(--space-2);
  height: 70px;
  border-radius: 15px;
  transition: background-color 200ms ease, transform 200ms ease;
}

.chat-item {
  display: grid;
  grid-template-areas: 'avatar body meta' 'avatar body meta';
  grid-template-columns: 58px 1fr auto;
  column-gap: 12px;
  height: 100%;
}

.chats-list__item.active {
  transform: translateY(-1px);
}

.chats-list__item:not(.active):hover {
  background-color: rgba(38, 58, 98, 0.30);
  cursor: pointer;
}

.chats-list__item:hover {
  background-color: #263a62;
  cursor: pointer;
}

.chat-item__avatar {
  grid-area: avatar;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  margin: auto 0;
  box-shadow:
    inset 0 1px 1px rgba(255, 255, 255, 0.08),
    0 0 0 1px rgba(255, 255, 255, 0.05);
}

.chat-item__body {
  grid-area: body;
  display: flex;
  flex-direction: column;
  /* align-items: end; */
  justify-content: space-around;
}

.chat-item__meta {
  grid-area: meta;
  display: flex;
  flex-direction: column;
  align-items: end;
  justify-content: space-around;
}

.chat-item__last {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
  overflow: hidden;
  max-width: 180px;
  text-overflow: ellipsis;
  font-size: 13px;
  opacity: 0.8;

}

.chat-item__title {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
  overflow: hidden;
  max-width: 180px;
  text-overflow: ellipsis;
}

.chat-item__pin {
  background: rgba(112, 108, 186, 0.9);
  border-radius: 20px;
  min-width: 22px;
  height: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 6px;


  &-cont {
    line-height: 0;
    font-size: 12px;
  }
}

.textContent {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.message-meta {
  width: fit-content;
  /* position: absolute;
    bottom: 6px;
    right: 10px; */
  display: flex;
  /* flex-direction: row-reverse; */
  align-items: flex-start;
  gap: 2px;
}

.message-meta-time {
  font-size: 12px;
  /* opacity: 0.7; */
}

.message-meta-read {
  width: 14px;
  height: 12px;
  /* display: none; */
}
</style>