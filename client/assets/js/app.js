import {checkLogedIn} from './helpers.js';
import setup_header from './components/header.js';
import setup_users from './components/users.js';
import setup_popup from './components/popup.js';


((win, doc, log, si, ci, sto, loc) => {
    if(checkLogedIn()){
      setup_header();
      setup_users();
      setup_popup();
    }else{
      window.location.replace('/ElementorTest/client/');
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
  