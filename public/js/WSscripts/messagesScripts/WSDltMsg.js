function dltMessage(data) {

    var tweet = $('.tweet[data-id=' + data.message['id'] + ']');
    tweet.remove();
}

Echo.private('messageDelete')
    .listen('.msgDel', (data) => {
        dltMessage(data);
    });