import { getCookie } from './../helpers.js';

class UserService {
    constructor() {}
    getUsers = () => {
        return fetch('/ElementorTest/server/api.php/User/fetchUsers',{
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'Basic ' + getCookie('token')
            }), 
        })
    }

    getUser = (username) => {
        return fetch('/ElementorTest/server/api.php/User/getUser/' + username,{
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'Basic ' + getCookie('token')
            }), 
        })
    }

    logout = () => {
        return fetch('/ElementorTest/server/api.php/User/logout',{
            method: "POST",
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'Basic ' + getCookie('token')
            }), 
            body: JSON.stringify({username:getCookie('username')})
        })
    }
}

export default UserService