function banComment(data) {
    var comment = $('.comment[data-id='+ data.comment['id'] +']');
    comment.remove();

    var commentCount = data.commentCount;
    var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);

    var lastComment = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2);
    lastComment.show();


    if(commentCount < 4 ){
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('a').remove();
    }
}


Echo.private('commentBanned')
    .listen('.cmntBan', (data) => {
        banComment(data);
    });
