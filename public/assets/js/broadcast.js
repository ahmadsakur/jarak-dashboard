import Echo from 'laravel-echo'

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '5c1274b066777330ada0',
  cluster: 'ap1',
  forceTLS: true
});

var channel = Echo.channel('order');
channel.listen('order-update', function(data) {
  alert(JSON.stringify(data));
});