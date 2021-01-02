import setup_login from './components/login.js';
import {checkLogedIn} from './helpers.js';

((win, doc, log, si, ci, sto, loc) => {
    if( !checkLogedIn() ){
        setup_login();
    }else{
      window.location.replace('/ElementorTest/client/main.html');
    }
})(
  window, 
  document, 
  console, 
  setInterval, 
  clearInterval, 
  setTimeout, 
  window.location
);

