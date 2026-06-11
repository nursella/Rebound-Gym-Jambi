// Notification System

// Check for new notifications every 30 seconds
function startNotificationPolling() {
    setInterval(function() {
        checkNewNotifications();
    }, 30000); // 30 seconds
}

// Check new notifications
function checkNewNotifications() {
    fetch(BASE_URL + 'api/get_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.count > 0) {
                updateNotificationBadge(data.count);
                if (data.unread > 0) {
                    showNewNotificationAlert(data.unread);
                }
            }
        })
        .catch(error => console.error('Error checking notifications:', error));
}

// Update notification badge
function updateNotificationBadge(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline' : 'none';
    }
}

// Show new notification alert
function showNewNotificationAlert(count) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification('Rebound Gym Jambi', {
            body: `Anda memiliki ${count} notifikasi baru`,
            icon: BASE_URL + 'assets/images/logo.png'
        });
    }
}

// Request notification permission
function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

// Mark notification as read
function markNotificationAsRead(notificationId) {
    fetch(BASE_URL + 'api/mark_notification_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + notificationId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                const currentCount = parseInt(badge.textContent);
                if (currentCount > 0) {
                    badge.textContent = currentCount - 1;
                    if (currentCount - 1 === 0) {
                        badge.style.display = 'none';
                    }
                }
            }
        }
    });
}

// Mark all notifications as read
function markAllNotificationsAsRead() {
    fetch(BASE_URL + 'api/mark_all_notifications_read.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        }
    });
}

// Initialize notification system
document.addEventListener('DOMContentLoaded', function() {
    requestNotificationPermission();
    startNotificationPolling();
});