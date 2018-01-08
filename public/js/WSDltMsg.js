var pusher = new Pusher('4ddf59eb5af2754e89f0', {
    cluster: 'eu',
    encrypted: true
});

var channel = pusher.subscribe('messageDelete');
channel.bind('msgDel', function (data) {

    $('.tweet[data-id='+ data.message['id'] +']').remove();

});