import {botErr, botInfo} from "../utils";

let doc = window.document;
export async function initFirebase(params = {}) {
    console.log('initFirebase:');
    let noop = () => {};
    const onReceived = params.onReceived || noop;
    const onRefreshed = params.onRefreshed || noop;
    const onMessage = params.onMessage || noop;
    const onError = params.onError || noop;


    let firebaseHost = 'https://www.gstatic.com/firebasejs/7.6.1/';
    let scripts = [
        'firebase-app.js',
        //   'firebase-analytics.js',
        'firebase-auth.js',
        //    'firebase-firestore.js',
        'firebase-messaging.js'
    ];

    for (let i = 0; i < scripts.length; i++) {
        await loadScript(firebaseHost + scripts[i]);
    }

    if (!window.firebase) {
        botErr('No firebase sdk found')
        return;
    }

    if (!window.firebase.messaging) {
        botErr('No firebase.messaging sdk found')
        return;
    }

    let firebase = window.firebase;

    var firebaseConfig = {
        apiKey: "AIzaSyCVvfuENLOY7vsDP8Qgj3oDbz1TLg7IJ8U",
        authDomain: "happynest-vn.firebaseapp.com",
        projectId: "happynest-vn",
        storageBucket: "happynest-vn.appspot.com",
        messagingSenderId: "742582317497",
        appId: "1:742582317497:web:9eb40340c11c5c97668668",
        measurementId: "G-5Z0FBDFHM0",
        databaseURL: "https://happynest-vn-default-rtdb.asia-southeast1.firebasedatabase.app"
    };

    firebase.initializeApp(firebaseConfig);


    const messaging = firebase.messaging();
    messaging.usePublicVapidKey("BHSEznB66w_b4XxKDXv5lNDSq5MTnPRQde-IwVnBVIvGar1AEbfPaq0WqHa3sJnASp0VmFCqAiRlnXD7EKI6uHI");


  messaging.requestPermission().then(function() {
        botInfo('Notification permission granted.');
        // TODO(developer): Retrieve an Instance ID token for use with FCM.
        messaging.getToken().then(function(currentToken) {

            if (currentToken) {
                // sendTokenToServer(currentToken);
                ///updateUIForPushEnabled(currentToken);
                onReceived(currentToken);
            } else {
                // Show permission request.
                botInfo('No Instance ID token available. Request permission to generate one.');
                // Show permission UI.
                //updateUIForPushPermissionRequired();
                ///etTokenSentToServer(false);
            }
        }).catch(function(err) {
            botErr(err);
            onError(err);
            // console.log('An error occurred while retrieving token. ', err);
            // showToken('Error retrieving Instance ID token. ', err);
            ///setTokenSentToServer(false);
        });
    }).catch(function(err) {
        onError(err);
        botErr(err);
    });

    messaging.onTokenRefresh(function() {
        messaging.getToken().then(function(refreshedToken) {
            botErr('Token refreshed.');
            onRefreshed(refreshedToken);
            // Indicate that the new Instance ID token has not yet been sent to the
            // app server.
            //   setTokenSentToServer(false);
            // Send Instance ID token to app server.
            /// sendTokenToServer(refreshedToken);
            // ...
        }).catch(function(err) {
            onError(err);
            //  console.log('Unable to retrieve refreshed token ', err);
            botErr(err);
        });
    });

    messaging.onMessage(function(payload) {
        //console.log('Message received. ', payload);
        onMessage(payload)
        // ...
    });
}


// https://www.gstatic.com/firebasejs/7.6.0/firebase-app.js
// https://www.gstatic.com/firebasejs/7.6.0/firebase-analytics.js
// https://www.gstatic.com/firebasejs/7.6.0/firebase-auth.js
// https://www.gstatic.com/firebasejs/7.6.0/firebase-firestore.js
// https://www.gstatic.com/firebasejs/7.6.0/firebase-messaging.js

function loadScript(link) {
    console.log('loadScript:', link);
    return new Promise((resolve, reject) => {
        let script = doc.createElement('script');

        script.src = link;
        script.onload = function() {
            resolve()
        }
        script.onerror = function() {
            reject()
        }
        doc.head.appendChild(script)
    })

}
