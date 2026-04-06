<script setup>

import { computed, ref, watch, nextTick, onMounted } from 'vue';
import { Check, CheckCheck } from '@lucide/vue';

const props = defineProps({
    chat: Object,
    currentUserId: Number,
});

const chatRef = ref(null);

const timeFormat = (unixSeconds ) => {
    if (!unixSeconds) return '';
    const date = new Date(unixSeconds * 1000);
    return date.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
}

const groupedMessages = computed(() => {
    if (!props.chat?.messages?.length) return [];
    const groups = [];

    props.chat.messages.forEach(message => {
        const lastGroup = groups[groups.length - 1];

        if (!lastGroup || message.authorId !== lastGroup.authorId) {
            groups.push({
                authorId: message.authorId,
                messages: [message]
            })
            return
        }
        lastGroup.messages.push(message);
    });
    return groups;
})

const autoScrollBottom = async (behavior = "instant") => {
    await nextTick();
    const chat = chatRef.value;
    if (!chat) return;

    chat.scrollTo({
        top: chat.scrollHeight,
        behavior
    })
}

onMounted(() => { autoScrollBottom() })

watch(
    () => props.chat?.id,
    () => autoScrollBottom()
);

watch(
    () => props.chat?.messages?.length,
    () => autoScrollBottom('smooth')
);
</script>

<template>
    <div v-if="!props.chat?.messages?.length" class="textContent">
        <span>Начните общение прямо сейчас!</span>
    </div>
    <div v-else class="chat-messages" ref="chatRef">
        <ul class="message-groups">
            <li class="message-group" v-for="group in groupedMessages" :key="group.messages[0].id"
                :class="{ 'message-group--me': group.authorId === props.currentUserId }">
                <div class="message-group__avatar avatar" :style="{ backgroundImage: `url(${chat.avatarUrl})` }">
                </div>
                <div class="message-group__body">
                    <ul class="message-group__list">
                        <li class="message-group__item" v-for="message in group.messages" :key="message.id">
                            <div class="message-bubble">
                                <span class="message-bubble__text">{{ message.text }}</span>
                                <div class="message-meta">
                                    <div class="message-meta-read">
                                        <span v-if="message.readAt">
                                            <CheckCheck />
                                        </span>
                                        <span v-else >
                                            <Check />
                                        </span>
                                    </div>
                                    <div class="message-meta-time">{{ timeFormat(message.updatedAt) }}</div>
                                </div>
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
</template>

<style lang="scss" scoped>
.chat-messages {
    flex: 1;
    overflow: auto;
    max-width: 100%;
    overflow-x: hidden;
    padding: 0 15px;
    padding-right: 10px;
}

.message-bubble {
    width: fit-content;
    max-width: 60%;
    position: relative;
    padding: 10px 15px;
    padding-right: 50px;
    line-height: 1.25;
    background: #1F2B50;

    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    border-bottom-right-radius: 18px;
    border-bottom-left-radius: 0;

    &__text {
        word-break: break-word;
        white-space: pre-wrap;
    }
}

.message-group {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    gap: 15px;
    margin-top: 12px;

    &__body {
        width: 100%;
    }

    &__avatar {
        min-width: 36px;
        min-height: 36px;
        border-radius: 50%;
    }

    &__list {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    &__item:not(:first-child) {
        .message-bubble {
            border-top-left-radius: 8px;
        }
    }

    &__item:not(:last-child) {
        .message-bubble {
            border-bottom-left-radius: 8px;
        }

        .svg-appendix {
            display: none;
        }
    }

    .svg-appendix {
        position: absolute;
        bottom: -1px;
        left: -8px;
        display: block;
        fill: #1F2B50;
        transform: scaleX(1);
        flex-shrink: 0;

        g {
            transform: translateY(-0.8px);
        }
    }
}

.message-group--me {
    padding-right: 6px;
    flex-direction: row-reverse;

    .message-bubble {
        margin-left: auto;
        background: #3E457F;
        padding-right: 60px;

        border-top-left-radius: 18px !important;
        border-top-right-radius: 18px;
        border-bottom-left-radius: 18px !important;
        border-bottom-right-radius: 0;
    }

    .svg-appendix {
        right: -8px;
        left: auto;
        fill: #3E457F;
        transform: scaleX(-1);

        g {
            transform: translateY(-0.8px);
        }
    }

    .message-meta {
        right: 6px;
    }

    .message-meta-read {
        display: block;
    }

    .message-group__item:not(:first-child) {
        .message-bubble {
            border-top-right-radius: 8px;
        }
    }

    .message-group__item:not(:last-child) {
        .message-bubble {
            border-bottom-right-radius: 8px;
        }

        .svg-appendix {
            display: none;
        }
    }

    .message-group__avatar {
        display: none;
    }
}

.message-meta {
    width: fit-content;
    position: absolute;
    bottom: 6px;
    right: 10px;
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    gap: 2px;
}

.message-meta-time {
    font-size: 12px;
    opacity: 0.7;
}

.message-meta-read {
    width: 14px;
    height: 12px;
    display: none;
}
</style>