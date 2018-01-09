function banMessage(data) {

    var tweet = $('.tweet[data-id='+ data.message['id'] +']');
    tweet.remove();
}

var pusher = new Pusher('4ddf59eb5af2754e89f0', {
    cluster: 'eu',
    encrypted: true
});

var channel = pusher.subscribe('messageBanned');
channel.bind('msgBan', function (data) {

   banMessage(data);

});
