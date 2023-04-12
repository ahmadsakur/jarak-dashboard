window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

var pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
    cluster: "ap1",
});

var channel = pusher.subscribe("order");

// Order update event
channel.bind("order-update", function (data) {
    if (data) {
        var notifications = JSON.parse(localStorage.getItem("notifications"));
        if (notifications) {
            notifications.push(data);
            localStorage.setItem(
                "notifications",
                JSON.stringify(notifications)
            );
        } else {
            var notification = [];
            notification.push(data);
            localStorage.setItem("notifications", JSON.stringify(notification));
        }
    }
    getNotification();
});

const getNotification = () => {
    var notifications = JSON.parse(localStorage.getItem("notifications"));
    if (notifications) {
        var count = notifications.length;
        setNotification(count);
    } else {
        return;
    }
};
const setNotification = (count) => {
    var badgeHtml = `
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${count}
            <span class="visually-hidden">unread messages</span>
        </span>`;
    document.getElementById("notificationBadge").innerHTML = badgeHtml;
};

// Order-create event

channel.bind("order-created", function (data) {
    appendToast(data);
});

const appendToast = (data) => {
    const alertMessage = `
    <div class="alert shadow-sm bg-body-tertiary rounded alert-dismissible fade show" role="alert" style="background: #e9ecef">
      <span class="alert-icon text-dark">
        <i class="fas fa-bell"></i>
      </span>
      <span class="alert-text text-dark text-sm ms-1"><strong>New Order!</strong> Check it now!</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fas fa-times text-dark"></i></span>
      </button>
    </div>
  `;
    const alertContainer = document.getElementById("alertContainer");
    alertContainer.insertAdjacentHTML("beforeend", alertMessage);
};
