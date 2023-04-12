window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

var pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
    cluster: "ap1",
});

var channel = pusher.subscribe("order");
channel.bind("order-update", function (data) {
    // push data to local storage, if data is not empty and not null or undefined, 
    // then push data to local storage
    if (data) {
        var notifications = JSON.parse(localStorage.getItem("notifications"));
        if (notifications) {
            notifications.push(data);
            localStorage.setItem("notifications", JSON.stringify(notifications));
        } else {
            var notification = [];
            notification.push(data);
            localStorage.setItem("notifications", JSON.stringify(notification));
        }
    }
    getNotification();
});

// function to get notification badge
const getNotification = () => {
    var notifications = JSON.parse(localStorage.getItem("notifications"));
    if (notifications) {
        var count = notifications.length;
        setNotification(count);
    } else {
        return
    }
};
// function to set notification badge
const setNotification = (count) => {
    var badgeHtml = `
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${count}
            <span class="visually-hidden">unread messages</span>
        </span>`;
    document.getElementById("notificationBadge").innerHTML = badgeHtml;
};
