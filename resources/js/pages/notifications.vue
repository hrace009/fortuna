<template>
   <div class="m-portlet m-portlet--bordered-semi m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text"></h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="#">
                    Marcar todas como lido
                </a>
            </div>
        </div>
       <div class="m-portlet__body pl-0 pr-0">
           <div class="item-list notification-list">
               <div v-for="(notification, index) in notifications" class="item item-sm cursor-pointer hoverable m--padding-left-20 m--padding-right-30 m--display-flex-center">
                   <span class="avatar-sm flex-none"><img :src="notification.data.author.avatar" class="m--img-rounded m--marginless m--img-centered"></span>
                   <div class="d-flex flex-column m--margin-left-10 flex-1">
                       <div>
                            <p class="m--margin-bottom-5">
                                <strong>{{ notification.data.author.name }}</strong>
                                {{ notification.data.type }} (<a href="" class="m-link">#{{ notification.data.body.id }})</a>
                            </p>
                            <p class="m--margin-bottom-0">
                                <em>"{{ notification.data.comment.message }}"</em>
                            </p>
                       </div>
                       <div class="text-with-icon">
                           <i class="la" :class="notification.data.icon"></i>
                          <span class="m--margin-left-5">{{ notification.created_at | timeFromNow }} </span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
</template>

<script>
    export default {

        middleware: 'auth',

        data: () => ({
            notifications: [],
        }),

        created () {
            this.getNotifications();
        },

        methods: {
            async getNotifications() {
                await axios.get(`/api/notifications/all`)
                    .then( ( response ) => {
                        this.notifications = response.data;
                    })
                }
        },

    }
</script>

<style>

.item-list .item {
    padding: 25px 0;
    border-top: 1px dashed #dfdfdf
}
.item-list .item .image {
    margin-right: 20px
}
.item-list .item h6 {
    font-size: 1.1rem
}
.item-list .item:first-child {
    border-top: none
}
@media (min-width:769px) {
    .item-list .item.item-sm {
        padding: 10px 0
    }
}
.item-list .item.hoverable:hover {
    background-color: #f7f8fa
}
.item-list .item.hoverable:hover .hidden-part {
    visibility: visible
}
.item-list .item .hidden-part {
    visibility: hidden
}
@media (max-width:768px) {
    .item-list .item > .d-flex,
    .item-list .item > .m--display-flex-center {
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        text-align: center
    }
    .item-list .item .info-icons {
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center
    }
    .item-list .item .buttons {
        margin-top: 10px
    }
    .item-list .item .buttons .btn {
        border: 1px solid #f1f1f1;
        margin-left: 5px
    }
    .item-list .item .buttons .btn:first-of-type {
        margin-left: 0
    }
    .item-list .item .image {
        margin-right: 0;
        margin-bottom: 10px
    }
    .item-list .item h6 a {
        display: block
    }

}


</style>