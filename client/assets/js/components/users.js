import Component from '../component-helper.js';
import  UserService from './../services/user-service.js';
import User from './user.js';

const setup_users = () => {
    'use strict';
    // Create the stopwatch
    class Users extends Component {
      constructor(props) {
        super(props); // calling super() so that the correct prototype chain is established.
        this.state = {...props};
        this.userService = new UserService();
      }

      template(props) {
        const { errMessage } = props;
              let template = `
          <div class="users-wrapper">
            ${this.state.data.usersList.map(user => {
              let userProps = {
                elem: document.createElement('user'),
                data: {
                  user:user
                }
              }
              let userComp = new User(userProps);
              let ee = userComp.render(userProps);
              return ee.innerHTML;
            })}
          </div>
        `;
            return template;
        }
        
        componentDidMount() {
            this.loadUsers();
            setInterval(() => {
              this.loadUsers();
            }, 3000);
            
        }

        loadUsers(){
          this.userService.getUsers()
          .then(res => {
            return res.json()
          })
          .then(res => {
            if( res ){
              this.setState({usersList: res.data});
            }
          })
          .catch(err => {
            console.error(err);
          })
        }
    }

    const INITIAL_STATE = {
        elem: 'users',
        data: {
          usersList: []
        }
    };
      
      // Define the new element
      let users = new Users(INITIAL_STATE);
      users.render();

}

export default setup_users;