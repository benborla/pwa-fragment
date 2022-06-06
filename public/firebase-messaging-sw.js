importScripts ('https://www.gstatic.com/firebasejs/5.6.0/firebase-app.js');
importScripts ('https://www.gstatic.com/firebasejs/5.6.0/firebase-messaging.js');

firebase.initializeApp ({'messagingSenderId': '128803504028'});

const messaging = firebase.messaging();


// messaging.setBackgroundMessageHandler (function(payload) {
  // dump('went here');
//
  // console.log('[firebase-messaging-sw.js] Received background message ', payload);
//
   // const data = payload.data;
   // const notificationTitle = data.title;
   // const notificationOptions = {
    // body: data.body
   // };
//
    // return self.registration.showNotification (notificationTitle,  notificationOptions);
// });
