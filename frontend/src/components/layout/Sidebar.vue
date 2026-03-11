<script setup>

const props = defineProps(
  {
    chats: Array,
    activeChatId: Number
  }
)
const emit = defineEmits(['select-chat']);

const selectChat = (id) => {
  if (props.activeChatId === id) {
    console.log('текущий чат');
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
  <div v-if="props.chats.length === 0"> у вас еще нет чатов</div>
  <div v-else>
    <ul class="chats-list">
      <li v-for="chat in props.chats" :key="chat.id" class="chats-list__item"
        :class="{ active: chat.id === props.activeChatId }">
        <div class="chat-item" @click="selectChat(chat.id)">
          <div class="chat-item__avatar" :style="{ backgroundImage: `url(${chat.avatarUrl})` }">
          </div>
          <div class="chat-item__body">
            <h3 class="chat-item__title">{{ chat.title }}</h3>
            <span class="chat-item__last">{{ chat.lastMessage }}</span>
          </div>
          <div class="chat-item__meta">
            <time class="chat-item__time">{{ computeDate(chat.updatedAt) }}</time>
            <span class="chat-item__pin">2</span>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<style>
.active {
  background-color: #263a62;
}

.chats-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  overflow-y: auto;
}

.chats-list__item {
  width: auto;
  padding: var(--space-3) var(--space-6);
  margin: var(--space-2);
  height: 70px;
  border-radius: var(--r-sm);
  transition: background-color 200ms ease, transform 200ms ease;
}

.chat-item {
  display: grid;
  grid-template-areas: 'avatar body meta' 'avatar body meta';
  grid-template-columns: 58px 1fr auto;
  column-gap: 12px;
  height: 100%;
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
  width: 57px;
  height: 57px;
  background-size: contain;
  margin: auto 0;
}

.chat-item__body {
  grid-area: body;
  display: flex;
  flex-direction: column;
  /* align-items: end; */
  justify-content: space-between;
}

.chat-item__meta {
  grid-area: meta;
  display: flex;
  flex-direction: column;
  align-items: end;
  justify-content: space-between;
}

.chat-item__last {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
  overflow: hidden;
}
</style>