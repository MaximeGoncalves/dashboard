export default class Gate {

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.role === 'admin'
    }
    isLeader(){
        return this.user.role === 'leader'
    }
    isUser(){
        return this.user.role === 'user'
    }

}
