<script setup>
import { Phone, Search, Ellipsis } from '@lucide/vue';
const props = defineProps({
    chat: Object,
});
</script>

<template>
    <div class="chat-header">
        <div class="chat-header__info">
            <h1 class="chat-header__title">{{ props.chat.title }}</h1>
            <span class="chat-header__status chat-header__status--online">В сети</span>
        </div>
        <div class="chat-header__actions">
            <ul class="chat-header__list">
                <li class="chat-header__item">
                    <button class="chat-header__btn" aria-label="Позвонить">
                        <Phone />
                    </button>
                </li>
                <li class="chat-header__item">
                    <button class="chat-header__btn" aria-label="Поиск">
                        <Search />
                    </button>
                </li>
                <li class="chat-header__item">
                    <button class="chat-header__btn" aria-label="Меню">
                        <Ellipsis />
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 0;
    padding: 15px;
    min-height: 68px;

    &__info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    &__title {
        font-weight: 600;
        font-size: 16px;
        line-height: 1.2;
    }

    &__status {
        font-size: 13px;
        line-height: 1.2;
        opacity: 0.72;
    }

    &__list {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    &__btn,
    &__item {
        height: 36px;
        width: 36px;
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

    &__btn:active {
        transform: translateY(0) scale(0.96);
        background: rgba(255, 255, 255, 0.1);
    }

    &__btn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 2px rgba(143, 155, 255, 0.35);
    }

    &__btn .lucide {
        width: 22px;
        height: 22px;
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
</style>