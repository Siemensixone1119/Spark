<script setup>
import Rail from '@/components/layout/Rail.vue';
import Chats from '@/components/layout/Chats.vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import Inspector from '@/components/layout/Inspector.vue';

import { ref, computed } from 'vue';
const API_HOST = 'http://192.168.56.102';
const chats = ref([
  {
    id: 1,
    title: 'Аня',
    unread: 2,
    online: true,
    lastMessage: 'Ты где? 🙂',
    updatedAt: 1771760599,
    messages: [
      { id: 1, author: 'other', text: 'Ты где? 🙂' },
      { id: 2, author: 'me', text: 'Сейчас подойду' },
    ],
    avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 2,
    title: 'Работа',
    unread: 0,
    online: false,
    lastMessage: 'Созвон в 14:00, ок?',
    updatedAt: 1771891200,
    messages: [
      { id: 1, author: 'other', text: 'Созвон в 14:00, ок?' },
      { id: 2, author: 'me', text: 'Да, буду' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 3,
    title: 'Учёба',
    unread: 5,
    online: true,
    lastMessage: 'Скинь конспект, пожалуйста',
    updatedAt: 1770331652,
    messages: [
      { id: 1, author: 'other', text: 'Скинь конспект, пожалуйста' },
      { id: 2, author: 'me', text: 'Ок, сейчас' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 4,
    title: 'Семья',
    unread: 1,
    online: false,
    lastMessage: 'Не забудь купить хлеб',
    updatedAt: 1767225600,
    messages: [
      { id: 1, author: 'other', text: 'Не забудь купить хлеб' },
      { id: 2, author: 'me', text: 'Хорошо!' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 5,
    title: 'Корабли (игра)',
    unread: 0,
    online: true,
    lastMessage: 'Зайди вечером, покажу новую пушку',
    updatedAt: 1754006400,
    messages: [
      { id: 1, author: 'other', text: 'Зайди вечером, покажу новую пушку' },
      { id: 2, author: 'me', text: 'Давай после 20:00' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },

  {
    id: 6,
    title: 'Катя',
    unread: 3,
    online: true,
    lastMessage: 'Хочешь кофе?',
    updatedAt: 1771890000,
    messages: [
      { id: 1, author: 'other', text: 'Хочешь кофе?' },
      { id: 2, author: 'me', text: 'Дааа 🙂' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 7,
    title: 'Команда: Spark',
    unread: 12,
    online: true,
    lastMessage: 'Залейте фиксы в develop',
    updatedAt: 1771880000,
    messages: [
      { id: 1, author: 'other', text: 'Залейте фиксы в develop' },
      { id: 2, author: 'me', text: 'Сейчас пушну' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 8,
    title: 'Заказчик',
    unread: 0,
    online: false,
    lastMessage: 'Ок, жду демо',
    updatedAt: 1771200000,
    messages: [
      { id: 1, author: 'other', text: 'Ок, жду демо' },
      { id: 2, author: 'me', text: 'Отправлю сегодня' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 9,
    title: 'Мама',
    unread: 1,
    online: false,
    lastMessage: 'Как ты?',
    updatedAt: 1769800000,
    messages: [
      { id: 1, author: 'other', text: 'Как ты?' },
      { id: 2, author: 'me', text: 'Всё хорошо ❤️' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 10,
    title: 'Папа',
    unread: 0,
    online: false,
    lastMessage: 'Позвони, когда будешь свободна',
    updatedAt: 1769000000,
    messages: [
      { id: 1, author: 'other', text: 'Позвони, когда будешь свободна' },
      { id: 2, author: 'me', text: 'Ок, вечером' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },

  {
    id: 11,
    title: 'Доставка',
    unread: 2,
    online: true,
    lastMessage: 'Курьер будет через 15 минут',
    updatedAt: 1771865000,
    messages: [
      { id: 1, author: 'other', text: 'Курьер будет через 15 минут' },
      { id: 2, author: 'me', text: 'Ок, жду' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 12,
    title: 'Соседи',
    unread: 0,
    online: false,
    lastMessage: 'Спасибо, всё тихо 🙂',
    updatedAt: 1770100000,
    messages: [
      { id: 1, author: 'other', text: 'Спасибо, всё тихо 🙂' },
      { id: 2, author: 'me', text: 'Отлично!' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 13,
    title: 'Фитнес',
    unread: 6,
    online: true,
    lastMessage: 'Тренировка перенесена на завтра',
    updatedAt: 1770600000,
    messages: [
      { id: 1, author: 'other', text: 'Тренировка перенесена на завтра' },
      { id: 2, author: 'me', text: 'Поняла' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 14,
    title: 'Банк',
    unread: 1,
    online: false,
    lastMessage: 'Подтвердите операцию',
    updatedAt: 1768000000,
    messages: [
      { id: 1, author: 'other', text: 'Подтвердите операцию' },
      { id: 2, author: 'me', text: 'Не я делала' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 15,
    title: 'Чат дома',
    unread: 0,
    online: true,
    lastMessage: 'Оплата ЖКХ прошла',
    updatedAt: 1770400000,
    messages: [
      { id: 1, author: 'other', text: 'Оплата ЖКХ прошла' },
      { id: 2, author: 'me', text: 'Супер' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },

  {
    id: 16,
    title: 'Клиент: лендинг',
    unread: 4,
    online: true,
    lastMessage: 'Можно заменить картинку в хиро?',
    updatedAt: 1771500000,
    messages: [
      { id: 1, author: 'other', text: 'Можно заменить картинку в хиро?' },
      { id: 2, author: 'me', text: 'Да, пришлите вариант' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 17,
    title: 'Поддержка хостинга',
    unread: 0,
    online: false,
    lastMessage: 'Заявка закрыта',
    updatedAt: 1767225600,
    messages: [
      { id: 1, author: 'other', text: 'Заявка закрыта' },
      { id: 2, author: 'me', text: 'Спасибо' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 18,
    title: 'Дизайн: брендбук',
    unread: 7,
    online: true,
    lastMessage: 'Скинь палитру и шрифты',
    updatedAt: 1771700000,
    messages: [
      { id: 1, author: 'other', text: 'Скинь палитру и шрифты' },
      { id: 2, author: 'me', text: 'Ок, сейчас соберу' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 19,
    title: 'Стажировка',
    unread: 1,
    online: false,
    lastMessage: 'Не забудь про отчёт',
    updatedAt: 1709208000,
    messages: [
      { id: 1, author: 'other', text: 'Не забудь про отчёт' },
      { id: 2, author: 'me', text: 'Приняла' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
  {
    id: 20,
    title: 'Тех. заметки',
    unread: 0,
    online: true,
    lastMessage: 'Nginx: location ^~ /uploads/',
    updatedAt: 1577836800,
    messages: [
      { id: 1, author: 'me', text: 'Nginx: location ^~ /uploads/' },
      { id: 2, author: 'me', text: 'Unix time: seconds -> *1000' },
    ],
    // avatarUrl: `${API_HOST}/uploads/avatars/test.png`,
  },
]);

const activeChatId = ref(chats.value[0]?.id ?? null);
const activeChat = computed(() => { return chats.value.find(c => c.id === activeChatId.value) ?? null })


if (activeChatId.value === null) {
  console.log('У вас еще нет чатов');
}

function onSelectChat(id) {
  activeChatId.value = id;
}

function onSendMessage(textMessage) {
  const chat = activeChat.value;
  if (!chat) return;

  chat.messages.push({ id: Date.now(), author: 'me', text: textMessage })
  chat.lastMessage = textMessage;
  chat.updatedAt = new Date().toISOString();
}

</script>


<template>
  <div class="app">
    <div class="app-shell">
      <div class="panel app-shell__rail">
        <Rail />
      </div>

      <div class="panel app-shell__sidebar">
        <Sidebar :chats="chats" :activeChatId="activeChatId" @select-chat="onSelectChat" />
      </div>

      <div class="panel app-shell__main">
        <Chats :chat="activeChat" @send-message="onSendMessage" />
      </div>

      <!-- <div class="panel app-shell__inspector">
        <Inspector />
      </div> -->
    </div>
  </div>
</template>


<style lang="scss" scoped></style>