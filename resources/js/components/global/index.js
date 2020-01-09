import Vue from 'vue'
import Tippy from 'v-tippy'
import 'v-tippy/dist/tippy.css'


Vue.use(Tippy)

// Ticket
import TicketView from './Ticket/View'
import TicketDetails from './Ticket/Details'
import TicketReplies from './Ticket/Replies'
import TicketReplyForm from './Ticket/ReplyForm'
import TicketFiles from './Ticket/Files'
import GameAccounts from './LinkedAccounts/Accounts'

[
  TicketView,
  TicketDetails,
  TicketReplies,
  TicketReplyForm,
  TicketFiles,
  GameAccounts
].forEach(Component => {
  Vue.component(Component.name, Component)
})