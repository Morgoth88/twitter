function deleteComment(data) {
    var comment = $('.comment[data-id='+ data.comment['id'] +']');
    comment.remove();
}

var pusher = new Pusher('4ddf59eb5af2754e89f0', {
    cluster: 'eu',
    encrypted: true
});

var channel = pusher.subscribe('commentDelete');
channel.bind('cmntDel', function (data) {

        deleteComment(data);

});
