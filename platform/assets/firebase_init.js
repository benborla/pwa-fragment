import { initializeApp } from 'firebase/app';
import { getMessaging, getToken } from "firebase/messaging";

// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAuJCmxX_cBCu5kgXFxy0l8qwe49CBdXSU",
  authDomain: "pwa-fragment.firebaseapp.com",
  projectId: "pwa-fragment",
  storageBucket: "pwa-fragment.appspot.com",
  messagingSenderId: "128803504028",
  appId: "1:128803504028:web:a1bdc70ce58be2c0b29259",
  measurementId: "G-BWCQ9ZBR1W"
};


const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

getToken(messaging, { vapidKey: 'BBsqxjiHKWLUn2_pdEvXNuBJii5Mtj8XJWvxuEfVuwG8DCKYxofcpcIEDJ1KWrwJy4I2_AZcnZQzIveJnCkZdiE' }).then((currentToken) => {
  if (currentToken) {
    // Send the token to your server and update the UI if necessary
    console.log({ currentToken });
  } else {
    // Show permission request UI
    console.log('No registration token available. Request permission to generate one.');
  }
}).catch((err) => {
  console.log('An error occurred while retrieving token. ', err);
});
