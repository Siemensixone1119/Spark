<script setup>

import { CircleUser, Settings, Users, PanelTop, MessageCircle, User } from '@lucide/vue';
import { ref, computed } from 'vue';

const topMenu = [
  { id: 'feed', label: 'Лента', icon: PanelTop },
  { id: 'messages', label: 'Сообщения', icon: MessageCircle },
  { id: 'friends', label: 'Друзья', icon: User },
  { id: 'communities', label: 'Сообщества', icon: Users },
];

const bottomMenu = [
  { id: 'settings', label: 'Настройки', icon: Settings },
  { id: 'profile', label: 'Профиль', icon: CircleUser },
];

const activeCategorieId = ref(null)

const selectCategorie = (id) => {
  if (activeCategorieId.value !== id) {
    activeCategorieId.value = id;
  }
}
</script>

<template>
  <div class="rail-list">
    <ul class="rail-list--top">
      <li v-for="item in topMenu" :key="item.id" class="rail-list__item">
        <button class="rail-list__btn" @click="selectCategorie(item.id)"
          :class="{ 'rail-list__item--active': activeCategorieId === item.id }">
          <component :is="item.icon" />
        </button>
      </li>
    </ul>

    <ul class="rail-list--bottom">
      <li v-for="item in bottomMenu" :key="item.id" class="rail-list__item">
        <button class="rail-list__btn" @click="selectCategorie(item.id)"
          :class="{ 'rail-list__item--active': activeCategorieId === item.id }">
          <component :is="item.icon" />
        </button>
      </li>
    </ul>
  </div>

</template>

<style lang="scss" scoped>
.rail-list {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  padding: 15px 10px;

  &__btn,
  &__item {
    height: 44px;
    width: 44px;
  }

  &__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 12px;
    background: transparent;
    color: #c6cbe2;
    cursor: pointer;
    transition:
      color 0.2s ease,
      background-color 0.2s ease,
      transform 0.2s ease,
      box-shadow 0.2s ease;
  }

  &__btn:hover {
    color: #f3f5ff;
    background: rgba(255, 255, 255, 0.06);
    transform: translateY(-1px);
  }

  &__btn.rail-list__item--active,
  &__btn:active {
    background: rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
    color: #fff;
  }

  &__btn:focus-visible {
    outline: none;
    box-shadow: 0 0 0 2px rgba(143, 155, 255, 0.35);
  }

  &__btn .lucide {
    width: 26px;
    height: 26px;
    stroke: currentColor;
    transition:
      color 0.2s ease,
      transform 0.2s ease,
      opacity 0.2s ease;
  }

  &__btn:hover .lucide {
    transform: scale(1.06);
  }

  &__btn:active .lucide {
    transform: scale(0.96);
  }
}

.rail-list--top,
.rail-list--bottom {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>