<template>
<div class="m-widget3">
    <div v-for="(reply, index) in replies" :key="index" class="kt-inbox__message kt-inbox__message--expanded">
        <div class="kt-inbox__head">
            <span class="kt-media"  data-toggle="expand" :style="`background-image: url(${reply.owner.avatar})`">
                <span></span>
            </span>
            <div class="kt-inbox__info">
                <div class="kt-inbox__author">
                    <span class="kt-inbox__name">{{ reply.owner.name }}</span>
                    <div class="kt-inbox__status">
                        1 day ago
                    </div>
                </div>
            </div>
            <div class="kt-inbox__actions">
                <div class="kt-inbox__datetime">Jul 15, 2019</div>
            </div>
        </div>
        <div class="kt-inbox__body">
            <div class="kt-inbox__text">
                {{ reply.message }}
            </div>
            <ticket-files :files="reply.attachments" />
        </div>
    </div>
</div>
</template>

<script>
export default {

    name: "TicketReplies",

    props: {
        replies: {
            type: Array,
            required: true,
        }
    },

    methods: {
        
    }
}
</script>
