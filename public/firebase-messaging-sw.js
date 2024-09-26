importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

// Initialize Firebase
var firebaseConfig = {
    apiKey: "AIzaSyA26H0meftr9e0V4r4gOZ0Efa7TfMow4v0",
    authDomain: "travel-e5a20.firebaseapp.com",
    projectId: "travel-e5a20",
    storageBucket: "travel-e5a20.appspot.com",
    messagingSenderId: "790866088682",
    appId: "1:790866088682:web:0343606c910eaba9363d93",
    measurementId: "G-D2DV6KJHJT"
};
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Handle background notifications
messaging.setBackgroundMessageHandler(function (payload) {
    console.log('Received background message: ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});
