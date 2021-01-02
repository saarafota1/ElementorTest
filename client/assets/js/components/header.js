import Component from '../component-helper.js';
import { setCookie, getCookie, deleteCookie } from '../helpers.js';
import  UserService from './../services/user-service.js';

const setup_header = () => {
  'use strict';
  // Create the stopwatch
	class Header extends Component {
    constructor(props) {
      super(props); // calling super() so that the correct prototype chain is established.
      this.state = {...props};
      this.userService = new UserService();
    }
    
    template(props) {
      const { heading } = props;
			let template = `
        <header>
          <div class='head-message'>
            <span>${heading} ${props.username?props.username:''}</span>
            <span id='logoutBtn'>Logout</span>
          </div>
        </header>
      `;
  		return template;
  	}
    
    logout = () => {
      this.userService.logout()
      .then(res => {
        deleteCookie('token');
        deleteCookie('username');
        window.location.href = '/ElementorTest/client/';
      })
      .catch(err => {
        console.log(err);
      })
    }

  	componentDidMount() {
      document.querySelector("#logoutBtn").addEventListener('click',this.logout);
    }
  
  }

  const INITIAL_STATE = {
    elem: 'header',
    data: {
      heading: 'Welcome.',
      username: getCookie('username')
	}
  };
  
  // Define the new element
  let header = new Header(INITIAL_STATE);
  header.render();
};

export default setup_header;