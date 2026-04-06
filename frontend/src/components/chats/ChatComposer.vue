<script setup>

import { SendHorizontal, Smile, Paperclip } from '@lucide/vue';
import { ref } from 'vue';

const emit = defineEmits(['send-message'])

const textMessage = ref('');
const inputRef = ref(null)

const MAX_H = 140;

const onSendMessage = () => {
    const message = textMessage.value.trim();
    if (message === '') return;

    emit('send-message', message);
    textMessage.value = '';

    const input = inputRef.value;
    if (!input) return;

    input.style.height = 'auto';
    input.style.overflowY = 'hidden';
}

const autoGrow = (e) => {
    const el = e.target;

    el.style.height = 'auto';
    const next = Math.min(el.scrollHeight, MAX_H);
    el.style.height = next + 'px';
    el.style.overflowY = (el.scrollHeight > MAX_H) ? 'auto' : 'hidden';
};
</script>
<template>
    <div class="chat-composer-cont">
        <div class="chat-composer">
            <div class="chat-composer__attachments">
                <button class="chat-composer__btn spark-btn" aria-label="Прикрепить файл">
                    <Paperclip />
                </button>
            </div>
            <textarea ref="inputRef" v-model="textMessage" class="chat-composer__input" @input="autoGrow"
                @keydown.enter.exact.prevent="onSendMessage" rows="1" placeholder="Написать сообщение..."></textarea>
            <div class="chat-composer__actions">
                <button class="chat-composer__btn spark-btn" aria-label="Смайлы">
                    <Smile />
                </button>
                <button class="chat-composer__btn chat-composer__btn--send spark-btn" @click="onSendMessage"
                    aria-label="Отправить">
                    <SendHorizontal />
                </button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.chat-composer-cont {
    padding: 15px;
}

.chat-composer {
    width: 100%;
    display: flex;
    gap: var(--space-4);
    background-color: #1F2B50;
    border-radius: 24px;
    padding: var(--space-4);
    overflow: visible;
    width: 100%;
    align-items: flex-end;
    transition: box-shadow 0.2s ease, border-color 0.2s ease;


    &__input {
        width: 100%;
        min-height: 20px;
        max-height: 140px;
        padding: 0 8px;
        background: transparent;
        border: none;
        outline: none;
        line-height: 20px;
        resize: none;
        overflow: hidden;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-word;
        margin: auto 0;
    }

    &__btn {
        width: 34px;
        height: 34px;
    }

    &__actions {
        display: flex;
        gap: 8px;
        width: 100px;
    }

    &__attachments {
        width: 45px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    &__btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 50%;
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
        width: 18px;
        height: 18px;
        stroke: currentColor;
        transition: transform 0.2s ease;
    }

    &__btn:hover .lucide {
        transform: scale(1.06);
    }

    &__btn--send {
        color: #eef1ff;
        background: rgba(143, 155, 255, 0.14);
    }

    &__btn--send:hover {
        color: #ffffff;
        background: rgba(143, 155, 255, 0.22);
    }

    &__btn--send:active {
        background: rgba(143, 155, 255, 0.28);
    }
}

.chat-composer:focus-within {
    box-shadow: 0 0 0 1px rgba(143, 155, 255, 0.35);
}
</style>