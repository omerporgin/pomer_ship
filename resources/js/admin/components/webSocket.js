window.Pusher = require('pusher-js');

/**
 *
 */
let WebSocket = function (user) {

    this.user = user;

    this.events();
};

/**
 *
 */
WebSocket.prototype.events = function () {

    this.pusher();

    this.callNotification();

}

/**
 * Triggers notification.
 *
 * This function can be called when 2 way comminication needed
 */
WebSocket.prototype.callNotification = function () {
    $.ajax({
        url: '/api_get_notifications'
    });
}

/**
 *
 * @param data
 */
WebSocket.prototype.pusher = function (data) {
    var self = this;
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = false;

    var pusher = new Pusher('26edf2d8a91725dda98a', {
        cluster: 'eu'
    });

    var eventName = 'client-messages.' + this.user.id;

    var channel = pusher.subscribe(eventName);

    channel.bind(eventName, function (data) {
        self.proceedData(data);
    });
}

/**
 *
 * @param data
 */
WebSocket.prototype.websocket = function (data) {
    let socket = new WebSocket("wss://javascript.info/article/websocket/demo/hello");

    socket.onopen = function (e) {
        alert("[open] Connection established");
        alert("Sending to server");
        socket.send("My name is John");
    };

    socket.onmessage = function (event) {
        alert(`[message] Data received from server: ${event.data}`);
    };

    socket.onclose = function (event) {
        if (event.wasClean) {
            alert(`[close] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
        } else {
            // e.g. server process killed or network down
            // event.code is usually 1006 in this case
            alert('[close] Connection died');
        }
    };
}

/**
 *
 * @param data
 */
WebSocket.prototype.proceedData = function (data) {

    var self = this;
    // Notifications
    self.updateBadge($('.badge-notificaitons'), data.totalNewNotifications);

    // Show notifications
    var newContent = $("#notification_container").html();
    $.each(data.notifications, function (index, item) {
        var template = $("#alert_template").html();
        template = template.replace('__MSG__', item.notification)
        template = template.replace('__DATE__', item.updated_at)
        newContent += template;
    })
    $("#notification_container").html(newContent);


    // Messages
    var totalNewMesages = data.messages.length;
    self.updateBadge($(".badge-messages"), totalNewMesages);

    // Show messages
    var newContent = $("#notification_container").html();
    $.each(data.messages, function (index, item) {
        var template = $("#message_template").html();
        template = template.replace('__MSG__', item.notification)
        template = template.replace('__DATE__', item.updated_at)
        newContent += template;
    })
    $("#messages_container").html(newContent);
}
/**
 *
 * @param data
 */
WebSocket.prototype.updateBadge = function (badge, total) {
    badge.removeClass("badge-secondary").removeClass("badge-danger");
    if (total == 0) {
        badge.addClass("badge-secondary");
    } else {
        badge.addClass("badge-danger");
    }
    badge.text(total);
}

export {
    WebSocket
}
