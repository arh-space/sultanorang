self.addEventListener('push', (event) => {
    if (event.data) {
		const pushData = event.data.json();
		event.waitUntil(self.registration.showNotification(pushData.title, pushData));
		console.log(pushData);
    } else {
    	console.log('No push data fetched');
    }
});

self.addEventListener('notificationclick', (event) => {
	event.notification.close();
	switch (event.action) {
		case 'action1':
			event.waitUntil(clients.openWindow(event.notification.data.pushActionButton1Url));
		break;
		case 'action2':
			event.waitUntil(clients.openWindow(event.notification.data.pushActionButton2Url));
		break;
		default:
			event.waitUntil(clients.openWindow(event.notification.data.url));
		break;
	}
});