import Component from '../component-helper.js';
import Popup from './popup.js';
import  UserService from './../services/user-service.js';

class User extends Component {
    constructor(props) {
      super(props); // calling super() so that the correct prototype chain is established.
      this.state = {...props};
      this.userService = new UserService();
    }
    
    template(props) {
      const { heading } = props;
            let template = `
            <div class='user-cube' onClick="showUserInfo('${this.state.data.user["username"]}')">
                <img src='assets/images/user.png' />
                <div class='data'>
                <div class='label-wrapper'>
                    <label>Username:</label>
                    <span>${this.state.data.user["username"]}</span>
                </div>
                <div class='label-wrapper'>
                    <label>Login Time:</label>
                    <span>${this.state.data.user["lastLogin"]}</span>
                </div>
                <div class='label-wrapper'>
                    <label>Last updated:</label>
                    <span>${this.state.data.user["lastUpdated"]}</span>
                </div>
                <div class='label-wrapper'>
                    <label>User IP:</label>
                    <span>${this.state.data.user["userIP"]}</span>
                </div>
                </div>
                <img src='assets/images/${this.state.data.user["connected"]?'logedin.png':'logedout.png'}' class='connected' />
                </div>`;
  		return template;
  	}

  	componentDidMount() {
    }

    showUserInfo(username) {
        this.userService.getUser(username)
        .then(res => res.json())
        .then(res => {
            let popupElem =  document.createElement('div');
            popupElem.setAttribute("id", "popupUserInfo");
            let popupProps = {
                elem: popupElem,
                data: {
                  user:res.user
                }
              }
              let popupComp = new Popup(popupProps);
              popupComp.render(popupProps);
        })
    }
};



export default User;