import {initializeApp} from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
import {getMessaging, getToken, onMessage} from 'https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js';
const vapidKey = 'BFwGd1kfoTpOrocnH0mlcVMcz7C6Nkbcc6mw6q8d5Pby5Dsq4pHbZ44oUMs1Sl0V27uwvvF-73MHdf1XvfJNzKw';


const firebaseConfig = {
    apiKey: "AIzaSyA26H0meftr9e0V4r4gOZ0Efa7TfMow4v0",
    authDomain: "travel-e5a20.firebaseapp.com",
    projectId: "travel-e5a20",
    storageBucket: "travel-e5a20.appspot.com",
    messagingSenderId: "790866088682",
    appId: "1:790866088682:web:0343606c910eaba9363d93",
    measurementId: "G-D2DV6KJHJT"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

const messaging = getMessaging(app);

// Request permission and get token
Notification.requestPermission().then((permission) => {
    if (permission === 'granted') {
        console.log('Notification permission granted.');
        getToken(messaging, {vapidKey: vapidKey})
            .then((currentToken) => {
                if (currentToken) {
                    console.log('Token:', currentToken);
                    // Send token to your server or use it as needed
                } else {
                    console.log('No registration token available. Request permission to generate one.');
                }
            })
            .catch((err) => {
                console.error('An error occurred while retrieving token. ', err);
            });
    } else {
        console.log('Unable to get permission to notify.');
    }
});

// Handle foreground messages
onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // Show notification or update UI as needed
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
    };
    new Notification(noteTitle, noteOptions);
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(function (registration) {
            console.log('Service Worker registered with scope:', registration.scope);
        }).catch(function (err) {
        console.log('Service Worker registration failed:', err);
    });
}
