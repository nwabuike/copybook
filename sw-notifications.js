// Service Worker for Push Notifications
// sw-notifications.js

self.addEventListener('install', function(event) {
    console.log('Service Worker installed');
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    console.log('Service Worker activated');
    return self.clients.claim();
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    event.waitUntil(
        clients.openWindow('/magicbook/customer_orderlist.php')
    );
});

self.addEventListener('push', function(event) {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/magicbook/images/logo.png',
            badge: '/magicbook/images/logo.png',
            vibrate: [200, 100, 200],
            data: {
                url: data.url || '/magicbook/customer_orderlist.php'
            }
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});
