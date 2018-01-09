function banComment(data) {
    var comment = $('.comment[data-id='+ data.comment['id'] +']');
    comment.remove();
}

var pusher = new Pusher('4ddf59eb5af2754e89f0', {
    cluster: 'eu',
    encrypted: true
});

var channel = pusher.subscribe('commentBanned');
channel.bind('cmntBan', function (data) {

    banComment(data);

});
