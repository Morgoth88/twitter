
var csrfToken = $('meta[name=csrf-token]').attr('content');


function updateComment(data) {

    var oldcomment = $('.comment[data-id=' + data.comment['old_id'] + ']');

    /*change tweet id and text*/
    var commentText = oldcomment.children('.comment-text');
    commentText.attr('data-id', data.comment['id']);
    commentText.text(data.comment['text']);

    oldcomment.attr('data-id', data.comment['id']);

    var newcomment = $('.comment[data-id=' + data.comment['id'] + ']');

    /*change message btn route to actual id*/
    newcomment.children('.comment-name').children('.up-del-links')
        .children('#banCommBtn').children('a')
        .attr('href', '/api/v1/ban/message/' + data.comment['message_id'] + '/comment/' + data.comment['id']);

    /*change update message btn to actual id*/
    newcomment.children('.comment-name').children('.up-del-links')
        .children('#msgUpdtBtn').attr('onclick', 'commentUpdateForm(' + data.comment['id'] + ')');

    /*change delete message btn form action route to actual id*/
    newcomment.children('.comment-name').children('.up-del-links')
        .children('form').attr('action', '/api/v1/tweet/' + data.comment['message_id'] + '/comment/' + data.comment['id']);
}

/****************************************************************************************/


Echo.private('commentUpdate')
    .listen('.cmntUpdt', (data) => {
        updateComment(data);
    });
