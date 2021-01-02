import Component from '../component-helper.js';
import {setCookie, getCookie} from '../helpers.js';

const setup_login = () => {
    'use strict';
    // Create the stopwatch
    class Login extends Component {
      constructor(props) {
        super(props); // calling super() so that the correct prototype chain is established.
        this.state = {...props};
      }

      template(props) {
        const { errMessage } = props;
              let template = `
          <div class="login-wrapper">
              <h3>Please Login</h3>
              <div class="control">
                  <input type="text" name="username" id="username" placeholder="Email">
                  <input type="password" name="password" id="password" placeholder="Password">
                  <div class="err-message">${errMessage?errMessage:''}</div>
                  <input type="button"  id="submitBtn" class="btn" value="Login" >
              </div>
          </div>
        `;
            return template;
        }

        componentWillUnmount() {
            // check if user loged in
            let token = getCookie('token');
            let username = getCookie('username');
            if( token && username ){
                window.location.replace('/ElementorTest/client/main.html');
                return;
            }
        }

        componentDidMount() {
            document.querySelector('#submitBtn').addEventListener('click',this.login);
        }

        login = () => {
            let username = document.querySelector('#username');
            let password = document.querySelector('#password');
            let data = {email:username.value, password:password.value};
            fetch('http://localhost/ElementorTest/server/api.php/User/login', 
            {
                method: 'POST',
                mode: 'cors',
                body: JSON.stringify(data)
            })
          .then(response => {
              return response.json()
            })
          .then(res => {
              if( res ){
                  if( res.success ){
                    setCookie('token',res.token,30 )
                    setCookie('username',username.value,30 )
                    window.location.href = '/ElementorTest/client/main.html'
                  }else{
                    document.querySelector('.err-message').innerText = res.message
                  }
              }
           })
           .catch(err => {
              document.querySelector('.err-message').innerText = err.message
              console.log(err);
           });
        }
    }

    const INITIAL_STATE = {
        elem: 'login',
        data: {
          username: '',
          password: ''
        }
    };
      
      // Define the new element
      let login = new Login(INITIAL_STATE);
      login.render();
}

export default setup_login;