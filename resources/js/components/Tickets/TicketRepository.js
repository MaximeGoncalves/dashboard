import axios from 'axios'
import Vue from 'vue'

export default {
    update(ticket){
        Vue.prototype.$Progress.start()
        let tech, type
        if (ticket.technician.user) {
            tech = ticket.technician.user.id
        }
        axios.put('/api/tickets/' + ticket.id, {
            technician: tech,
            source: ticket.source.id,
            importance: ticket.importance,
            state: ticket.state.id,
            type: ticket.type ? ticket.type.id : null
        }).then(response => {
            Vue.toasted.success("Le ticket à été mis à jour", {duration: 3000});
            Vue.prototype.$Progress.finish()
            // Fire.$emit('updateTicket', ticket.id)
            if(response.data.close){
                axios.post('/api/tickets/sendmail', {
                    ticket: response.data.ticket.id,
                    close: response.data.close
                })
            }
        })
    }
}
