<script setup>
import Rail from '@/layout/Rail.vue';
import Chats from '@/layout/Chats.vue';
import Sidebar from '@/layout/Sidebar.vue';
import Inspector from '@/layout/Inspector.vue';

import { ref, computed } from 'vue';
const API_HOST = 'http://localhost:5173/';
const currentUserId = 1;

const chats = ref([
  {
    id: 1,
    title: 'Аня',
    unread: 0,
    online: true,
    updatedAt: 1771760599,
    participantIds: [1, 2],
    messages: [
      { id: 1, authorId: 2, text: 'Ты где? 🙂', createdAt: 1771757779, updatedAt: 1771757779, readAt: 1771757839 },
      { id: 2, authorId: 2, text: 'Я уже пришла', createdAt: 1771757839, updatedAt: 1771757839, readAt: 1771757899 },
      { id: 3, authorId: 1, text: 'Сейчас подойду', createdAt: 1771757899, updatedAt: 1771757899, readAt: 1771757959 },
      { id: 4, authorId: 1, text: 'Почти дошла', createdAt: 1771757959, updatedAt: 1771757959, readAt: 1771758019 },
      { id: 5, authorId: 2, text: 'Хорошо', createdAt: 1771758019, updatedAt: 1771758019, readAt: 1771758079 },
      { id: 6, authorId: 2, text: 'Я возле входа стою', createdAt: 1771758079, updatedAt: 1771758079, readAt: 1771758139 },
      { id: 7, authorId: 2, text: 'Тут холодно вообще-то 😅', createdAt: 1771758139, updatedAt: 1771758139, readAt: 1771758199 },
      { id: 8, authorId: 1, text: 'Ахаха, потерпи чуть-чуть', createdAt: 1771758199, updatedAt: 1771758199, readAt: 1771758259 },
      { id: 9, authorId: 1, text: 'Я уже рядом с магазином', createdAt: 1771758259, updatedAt: 1771758259, readAt: 1771758319 },
      { id: 10, authorId: 2, text: 'Ты всегда так говоришь', createdAt: 1771758319, updatedAt: 1771758319, readAt: 1771758379 },
      { id: 11, authorId: 2, text: 'А потом ещё 15 минут идёшь', createdAt: 1771758379, updatedAt: 1771758379, readAt: 1771758439 },
      { id: 12, authorId: 1, text: 'Нет, сейчас реально быстро', createdAt: 1771758439, updatedAt: 1771758439, readAt: 1771758499 },
      { id: 13, authorId: 1, text: 'Я даже не зашла никуда по пути', createdAt: 1771758499, updatedAt: 1771758499, readAt: 1771758559 },
      { id: 14, authorId: 2, text: 'Это уже подозрительно', createdAt: 1771758559, updatedAt: 1771758559, readAt: 1771758619 },
      { id: 15, authorId: 2, text: 'Ты точно моя подруга?', createdAt: 1771758619, updatedAt: 1771758619, readAt: 1771758679 },
      { id: 16, authorId: 1, text: 'Нет, я твой личный курьер', createdAt: 1771758679, updatedAt: 1771758679, readAt: 1771758739 },
      { id: 17, authorId: 1, text: 'Доставляю себя к тебе', createdAt: 1771758739, updatedAt: 1771758739, readAt: 1771758799 },
      { id: 18, authorId: 1, text: 'Экспресс-доставка 😎', createdAt: 1771758799, updatedAt: 1771758799, readAt: 1771758859 },
      { id: 19, authorId: 2, text: 'Ладно, смешно', createdAt: 1771758859, updatedAt: 1771758859, readAt: 1771758919 },
      { id: 20, authorId: 2, text: 'Кстати, ты кофе хочешь?', createdAt: 1771758919, updatedAt: 1771758919, readAt: 1771758979 },
      { id: 21, authorId: 1, text: 'Даааа', createdAt: 1771758979, updatedAt: 1771758979, readAt: 1771759039 },
      { id: 22, authorId: 1, text: 'Очень', createdAt: 1771759039, updatedAt: 1771759039, readAt: 1771759099 },
      { id: 23, authorId: 1, text: 'Я весь день мечтала о кофе', createdAt: 1771759099, updatedAt: 1771759099, readAt: 1771759159 },
      { id: 24, authorId: 2, text: 'Тогда можем сначала зайти взять что-нибудь', createdAt: 1771759159, updatedAt: 1771759159, readAt: 1771759219 },
      { id: 25, authorId: 2, text: 'А потом уже пойти гулять', createdAt: 1771759219, updatedAt: 1771759219, readAt: 1771759279 },
      { id: 26, authorId: 1, text: 'Идеально', createdAt: 1771759279, updatedAt: 1771759279, readAt: 1771759339 },
      { id: 27, authorId: 1, text: 'Мне такое расписание нравится', createdAt: 1771759339, updatedAt: 1771759339, readAt: 1771759399 },
      { id: 28, authorId: 2, text: 'Только давай быстро, а то я правда замёрзла', createdAt: 1771759399, updatedAt: 1771759399, readAt: 1771759459 },
      { id: 29, authorId: 2, text: 'У меня уже руки ледяные', createdAt: 1771759459, updatedAt: 1771759459, readAt: 1771759519 },
      { id: 30, authorId: 1, text: 'Я вижу тебя кажется', createdAt: 1771759519, updatedAt: 1771759519, readAt: 1771759579 },
      { id: 31, authorId: 1, text: 'Ты в светлой куртке?', createdAt: 1771759579, updatedAt: 1771759579, readAt: 1771759639 },
      { id: 32, authorId: 2, text: 'Да', createdAt: 1771759639, updatedAt: 1771759639, readAt: 1771759699 },
      { id: 33, authorId: 2, text: 'И с недовольным лицом', createdAt: 1771759699, updatedAt: 1771759699, readAt: 1771759759 },
      { id: 34, authorId: 1, text: 'Это очень помогает поискам 😂', createdAt: 1771759759, updatedAt: 1771759759, readAt: 1771759819 },
      { id: 35, authorId: 1, text: 'Всё, иду к тебе', createdAt: 1771759819, updatedAt: 1771759819, readAt: 1771759879 },
      { id: 36, authorId: 2, text: 'Ну наконец-то', createdAt: 1771759879, updatedAt: 1771759879, readAt: 1771759939 },
      { id: 37, authorId: 2, text: 'Я уже думала домой уйти', createdAt: 1771759939, updatedAt: 1771759939, readAt: 1771759999 },
      { id: 38, authorId: 1, text: 'Не драматизируй', createdAt: 1771759999, updatedAt: 1771759999, readAt: 1771760059 },
      { id: 39, authorId: 1, text: 'Я тут буквально в двух шагах', createdAt: 1771760059, updatedAt: 1771760059, readAt: 1771760119 },
      { id: 40, authorId: 2, text: 'Если ты сейчас подойдёшь, я тебя прощу', createdAt: 1771760119, updatedAt: 1771760119, readAt: 1771760179 },
      { id: 41, authorId: 2, text: 'Может быть', createdAt: 1771760179, updatedAt: 1771760179, readAt: 1771760239 },
      { id: 42, authorId: 1, text: 'О, какие условия', createdAt: 1771760239, updatedAt: 1771760239, readAt: 1771760299 },
      { id: 43, authorId: 1, text: 'Ладно, тогда жду награду за скорость', createdAt: 1771760299, updatedAt: 1771760299, readAt: 1771760359 },
      { id: 44, authorId: 2, text: 'Кофе и моё хорошее настроение подойдут?', createdAt: 1771760359, updatedAt: 1771760359, readAt: 1771760419 },
      { id: 45, authorId: 2, text: 'Это максимум на сегодня', createdAt: 1771760419, updatedAt: 1771760419, readAt: 1771760479 },
      { id: 46, authorId: 1, text: 'Согласна', createdAt: 1771760479, updatedAt: 1771760479, readAt: 1771760539 },
      { id: 47, authorId: 1, text: 'Тогда жду тебя у входа 💫', createdAt: 1771760599, updatedAt: 1771760599, readAt: 1771760650 }
    ],
    lastMessage: { id: 47, authorId: 1, text: 'Тогда жду тебя у входа 💫', createdAt: 1771760599, updatedAt: 1771760599, readAt: 1771760650 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 2,
    title: 'Работа',
    unread: 0,
    online: false,
    updatedAt: 1771891200,
    participantIds: [1, 3],
    messages: [],
    lastMessage: null,
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 3,
    title: 'Учёба',
    unread: 5,
    online: true,
    updatedAt: 1770331652,
    participantIds: [1, 4],
    messages: [
      { id: 1, authorId: 4, text: 'Скинь конспект, пожалуйста', createdAt: 1770331592, updatedAt: 1770331592, readAt: 1770331652 },
      { id: 2, authorId: 1, text: 'Ок, сейчас', createdAt: 1770331652, updatedAt: 1770331652, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Ок, сейчас', createdAt: 1770331652, updatedAt: 1770331652, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 4,
    title: 'Семья',
    unread: 1,
    online: false,
    updatedAt: 1767225600,
    participantIds: [1, 5],
    messages: [
      { id: 1, authorId: 5, text: 'Не забудь купить хлеб', createdAt: 1767225540, updatedAt: 1767225540, readAt: 1767225600 },
      { id: 2, authorId: 1, text: 'Хорошо!', createdAt: 1767225600, updatedAt: 1767225600, readAt: 1767225800 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Хорошо!', createdAt: 1767225600, updatedAt: 1767225600, readAt: 1767225800 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 5,
    title: 'Корабли (игра)',
    unread: 0,
    online: true,
    updatedAt: 1754006400,
    participantIds: [1, 6],
    messages: [
      { id: 1, authorId: 6, text: 'Зайди вечером, покажу новую пушку', createdAt: 1754006340, updatedAt: 1754006340, readAt: 1754006400 },
      { id: 2, authorId: 1, text: 'Давай после 20:00', createdAt: 1754006400, updatedAt: 1754006400, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Давай после 20:00', createdAt: 1754006400, updatedAt: 1754006400, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 6,
    title: 'Катя',
    unread: 0,
    online: true,
    updatedAt: 1771890000,
    participantIds: [1, 7],
    messages: [
      { id: 1, authorId: 7, text: 'Хочешь кофе?', createdAt: 1771889940, updatedAt: 1771889940, readAt: 1771890000 },
      { id: 2, authorId: 1, text: 'Дааа 🙂', createdAt: 1771890000, updatedAt: 1771890000, readAt: 1771890060 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Дааа 🙂', createdAt: 1771890000, updatedAt: 1771890000, readAt: 1771890060 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 7,
    title: 'Команда: Spark',
    unread: 12,
    online: true,
    updatedAt: 1771880000,
    participantIds: [1, 8],
    messages: [
      { id: 1, authorId: 8, text: 'Залейте фиксы в develop', createdAt: 1771879940, updatedAt: 1771879940, readAt: 1771880000 },
      { id: 2, authorId: 1, text: 'Сейчас пушну', createdAt: 1771880000, updatedAt: 1771880000, readAt: 1771880120 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Сейчас пушну', createdAt: 1771880000, updatedAt: 1771880000, readAt: 1771880120 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 8,
    title: 'Заказчик',
    unread: 0,
    online: false,
    updatedAt: 1771200000,
    participantIds: [1, 9],
    messages: [
      { id: 1, authorId: 9, text: 'Ок, жду демо', createdAt: 1771199940, updatedAt: 1771199940, readAt: 1771200000 },
      { id: 2, authorId: 1, text: 'Отправлю сегодня', createdAt: 1771200000, updatedAt: 1771200000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Отправлю сегодня', createdAt: 1771200000, updatedAt: 1771200000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 9,
    title: 'Мама',
    unread: 1,
    online: false,
    updatedAt: 1769800000,
    participantIds: [1, 10],
    messages: [
      { id: 1, authorId: 10, text: 'Как ты?', createdAt: 1769799940, updatedAt: 1769799940, readAt: 1769800000 },
      { id: 2, authorId: 1, text: 'Всё хорошо ❤️', createdAt: 1769800000, updatedAt: 1769800000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Всё хорошо ❤️', createdAt: 1769800000, updatedAt: 1769800000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 10,
    title: 'Папа',
    unread: 0,
    online: false,
    updatedAt: 1769000000,
    participantIds: [1, 11],
    messages: [
      { id: 1, authorId: 11, text: 'Позвони, когда будешь свободна', createdAt: 1768999940, updatedAt: 1768999940, readAt: 1769000000 },
      { id: 2, authorId: 1, text: 'Ок, вечером', createdAt: 1769000000, updatedAt: 1769000000, readAt: 1769000300 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Ок, вечером', createdAt: 1769000000, updatedAt: 1769000000, readAt: 1769000300 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 11,
    title: 'Доставка',
    unread: 2,
    online: true,
    updatedAt: 1771865000,
    participantIds: [1, 12],
    messages: [
      { id: 1, authorId: 12, text: 'Курьер будет через 15 минут', createdAt: 1771864940, updatedAt: 1771864940, readAt: 1771865000 },
      { id: 2, authorId: 1, text: 'Ок, жду', createdAt: 1771865000, updatedAt: 1771865000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Ок, жду', createdAt: 1771865000, updatedAt: 1771865000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 12,
    title: 'Соседи',
    unread: 0,
    online: false,
    updatedAt: 1770100000,
    participantIds: [1, 13],
    messages: [
      { id: 1, authorId: 13, text: 'Спасибо, всё тихо 🙂', createdAt: 1770099940, updatedAt: 1770099940, readAt: 1770100000 },
      { id: 2, authorId: 1, text: 'Отлично!', createdAt: 1770100000, updatedAt: 1770100000, readAt: 1770100300 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Отлично!', createdAt: 1770100000, updatedAt: 1770100000, readAt: 1770100300 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 13,
    title: 'Фитнес',
    unread: 6,
    online: true,
    updatedAt: 1770600000,
    participantIds: [1, 14],
    messages: [
      { id: 1, authorId: 14, text: 'Тренировка перенесена на завтра', createdAt: 1770599940, updatedAt: 1770599940, readAt: 1770600000 },
      { id: 2, authorId: 1, text: 'Поняла', createdAt: 1770600000, updatedAt: 1770600000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Поняла', createdAt: 1770600000, updatedAt: 1770600000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 14,
    title: 'Банк',
    unread: 1,
    online: false,
    updatedAt: 1768000000,
    participantIds: [1, 15],
    messages: [
      { id: 1, authorId: 15, text: 'Подтвердите операцию', createdAt: 1767999940, updatedAt: 1767999940, readAt: 1768000000 },
      { id: 2, authorId: 1, text: 'Не я делала', createdAt: 1768000000, updatedAt: 1768000000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Не я делала', createdAt: 1768000000, updatedAt: 1768000000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 15,
    title: 'Чат дома',
    unread: 0,
    online: true,
    updatedAt: 1770400000,
    participantIds: [1, 16],
    messages: [
      { id: 1, authorId: 16, text: 'Оплата ЖКХ прошла', createdAt: 1770399940, updatedAt: 1770399940, readAt: 1770400000 },
      { id: 2, authorId: 1, text: 'Супер', createdAt: 1770400000, updatedAt: 1770400000, readAt: 1770400200 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Супер', createdAt: 1770400000, updatedAt: 1770400000, readAt: 1770400200 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 16,
    title: 'Клиент: лендинг',
    unread: 4,
    online: true,
    updatedAt: 1771500000,
    participantIds: [1, 17],
    messages: [
      { id: 1, authorId: 17, text: 'Можно заменить картинку в хиро?', createdAt: 1771499940, updatedAt: 1771499940, readAt: 1771500000 },
      { id: 2, authorId: 1, text: 'Да, пришлите вариант', createdAt: 1771500000, updatedAt: 1771500000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Да, пришлите вариант', createdAt: 1771500000, updatedAt: 1771500000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 17,
    title: 'Поддержка хостинга',
    unread: 0,
    online: false,
    updatedAt: 1767225600,
    participantIds: [1, 18],
    messages: [
      { id: 1, authorId: 18, text: 'Заявка закрыта', createdAt: 1767225540, updatedAt: 1767225540, readAt: 1767225600 },
      { id: 2, authorId: 1, text: 'Спасибо', createdAt: 1767225600, updatedAt: 1767225600, readAt: 1767225900 },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Спасибо', createdAt: 1767225600, updatedAt: 1767225600, readAt: 1767225900 },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 18,
    title: 'Дизайн: брендбук',
    unread: 7,
    online: true,
    updatedAt: 1771700000,
    participantIds: [1, 19],
    messages: [
      { id: 1, authorId: 19, text: 'Скинь палитру и шрифты', createdAt: 1771699940, updatedAt: 1771699940, readAt: 1771700000 },
      { id: 2, authorId: 1, text: 'Ок, сейчас соберу', createdAt: 1771700000, updatedAt: 1771700000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Ок, сейчас соберу', createdAt: 1771700000, updatedAt: 1771700000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 19,
    title: 'Стажировка',
    unread: 1,
    online: false,
    updatedAt: 1709208000,
    participantIds: [1, 20],
    messages: [
      { id: 1, authorId: 20, text: 'Не забудь про отчёт', createdAt: 1709207940, updatedAt: 1709207940, readAt: 1709208000 },
      { id: 2, authorId: 1, text: 'Приняла', createdAt: 1709208000, updatedAt: 1709208000, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Приняла', createdAt: 1709208000, updatedAt: 1709208000, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
  {
    id: 20,
    title: 'Тех. заметки',
    unread: 0,
    online: true,
    updatedAt: 1577836800,
    participantIds: [1],
    messages: [
      { id: 1, authorId: 1, text: 'Nginx: location ^~ /uploads/', createdAt: 1577836740, updatedAt: 1577836740, readAt: null },
      { id: 2, authorId: 1, text: 'Unix time: seconds -> *1000', createdAt: 1577836800, updatedAt: 1577836800, readAt: null },
    ],
    lastMessage: { id: 2, authorId: 1, text: 'Unix time: seconds -> *1000', createdAt: 1577836800, updatedAt: 1577836800, readAt: null },
    avatarUrl: 'https://www.shutterstock.com/image-vector/default-avatar-social-media-display-600nw-2632690107.jpg',
  },
])

const activeChatId = ref(null);
const activeChat = computed(() => { return chats.value.find(c => c.id === activeChatId.value) ?? null })

function onSelectChat(id) {
  activeChatId.value = id;
}

function onSendMessage(textMessage) {
  const chat = activeChat.value;
  if (!chat) return;

  const timestamp = Math.floor(Date.now() / 1000)

  const message = { id: Date.now(), authorId: currentUserId, text: textMessage, createdAt: timestamp, updatedAt: timestamp, readAt: null }
  chat.messages.push(message)
  chat.lastMessage = message;
  chat.updatedAt = Date.now();
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
        <Chats :chat="activeChat" :current-user-id="currentUserId" @send-message="onSendMessage" />
      </div>

      <!-- <div class="panel app-shell__inspector">
        <Inspector />
      </div> -->
    </div>
  </div>
</template>


<style lang="scss" scoped></style>