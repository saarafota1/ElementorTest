import Component from '../component-helper.js';
import { setCookie, getCookie, deleteCookie } from '../helpers.js';
import  UserService from './../services/user-service.js';

const setup_popup = () => {
  'use strict';
  // Create the stopwatch
	class Popup extends Component {
    constructor(props) {
      super(props); // calling super() so that the correct prototype chain is established.
      this.state = {...props};
      this.userService = new UserService();
    }
    
    template(props) {
      const { heading } = props;
			let template = `
          <div class='popup'>
              <div class="close-popup">X</div>
              <div>User Info</div>
              <img src='assets/images/user.png' class='img-small' />
              <div class='data'>
                <div class='label-wrapper'>
                    <label>User User-Agent:</label>
                    <span>${this.state.data.user["userAgent"]}</span>
                </div>
                <div class='label-wrapper'>
                    <label>Register Time:</label>
                    <span>${this.state.data.user["registerTime"]}</span>
                </div>
                <div class='label-wrapper'>
                    <label>Logins Count:</label>
                    <span>${this.state.data.user["loginCount"]}</span>
                </div>
            </div>
          </div>
      `;
  		return template;
  	}

  	componentDidMount() {
      document.querySelector('.close-popup').addEventListener('click',this.closePopup);
    }

    closePopup() {
      let allPopups = document.querySelectorAll('#popupUserInfo');
      allPopups.map(elem => {
        elem.remove();
      })
    }
  
  }

};

export default setup_popup;