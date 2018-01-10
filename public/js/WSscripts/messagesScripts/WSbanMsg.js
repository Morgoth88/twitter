function banMessage(data) {

    var tweet = $('.tweet[data-id='+ data.message['id'] +']');
    tweet.remove();
}


Echo.private('messageBanned')
    .listen('.msgBan', (data) => {
        banMessage(data);
    });
