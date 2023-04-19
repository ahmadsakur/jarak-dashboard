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
    setNotificationContent(data.message);
});

const getNotification = () => {
    var badgeHtml = `
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" id="notificationBadge">
    <i class="fas fa-info"></i>
        <span class="visually-hidden">unread messages</span>
    </span>`;
    document.getElementById("notificationBadge").innerHTML = badgeHtml;
};

const setNotificationContent = (data) => {
    let notificationContainer = document.getElementById(
        "notificationContainer"
    );

    let notificationContent = `
    <li class="mb-2">
        <a class="dropdown-item border-radius-md" href="javascript:;">
            <div class="d-flex py-1">
                <div class="my-auto">
                    <img src="./img/small-logos/logo-info.svg"
                        class="avatar avatar-sm text-light me-3 ">
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1 text-dark" style="color: #344767 !important;">
                        <span class="font-weight-bold text-dark">Order ${data.status}</span> by ${data.id}
                    </h6>
                    <p class="text-xs mb-0 text-dark">
                        <i class="fas fa-shopping-cart  me-1"></i>
                        Table UPDATE PAYLOAD
                    </p>
                </div>
            </div>
        </a>
    </li>`;

    notificationContainer.insertAdjacentHTML("afterbegin", notificationContent);
};

// onclick event for notification badge
const notificationBadge = document.getElementById("notificationBadge");
const notificationContainer = document.getElementById("notificationContainer");
notificationContainer.onclick = function () {
    notificationBadge.remove();
};

// load notification on container
const notificationDropdown = document.getElementById("notificationDropdown");
notificationDropdown.addEventListener("click", function () {
    notificationContainer.innerHTML = "";
    const datas = localStorage.getItem("notifications");
    if (datas) {
        const data = JSON.parse(datas);
        data.forEach((element) => {
            setNotificationContent(element.message);
        });
    }
});

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
