var csrfToken = $('meta[name=csrf-token]').attr('content');

function createComment(data) {

    var userName = (authUserRole == 0 )
        ? data.user['userName']
        : '<a href="/api/v1/user/' + data.user['user_id'] + '">' + data.user['userName'] + '</a>';

    var banBtn = (authUserRole == 0 )
        ? ''
        : '<button id="banUserBtn">' +
        '<a href="/api/v1/ban/user/' + data.user['user_id'] + '">' +
        '<i class="fa fa-ban" aria-hidden="true"></i></a>' +
        '</button>';

    var msgBan = (authUserRole == 0 )
        ? ''
        : '<button id="banCommBtn">' +
        '<a href="/api/v1/ban/message/' + data.comment['message_id'] + '/comment/' + data.comment['id'] + '">' +
        '<i class="fa fa-ban" aria-hidden="true"></i></a>' +
        '</button>';

    //doimplementovat odpocet dvou minut
    var updtBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + data.comment['id'] + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';

    //doimplementovat odpocet dvou minut
    var dltBtn = (authUserId == data.user['user_id'])
        ? '<form method="POST" action="/api/v1/tweet/' + data.comment['message_id'] + '/comment/' + data.comment['id'] + '">' +
        '<input type="hidden"  name="_token" value="' + csrfToken + '">' +
        '<input type="hidden" name="_method" value="DELETE">' +
        '<button id="msgDltBtn" type="submit">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>' +
        '</form>'
        : '';

    if ($('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').length < 1) {

        $('.tweet[data-id=' + data.comment['message_id'] + ']').append('<div class="comments-container"></div>')
    }

    var html =
        '<div class="comment" data-id="' + data.comment['id'] + '"> ' +
        '<div class="comment-name">' + userName + '' + banBtn +
        '<span class="up-del-links">' + msgBan + updtBtn + dltBtn + '</span>' +
        '<span class="comment-time">' + moment().startOf(data.comment['created_at']).fromNow() + '</span>' +
        '</div>' +
        '<div class="comment-text" data-comment-id="' + data.comment['id'] + '" data-tweet-id="' + data.comment['message_id'] + '">' +
        data.comment['text'] + '</div>' +
        '</div>';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').prepend(html);

    var commentCount = data.commentCount;
    var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);
}
/****************************************************************************************/


function deleteLastComment(data) {
    var lastComment = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2);
    lastComment.hide();
}

/****************************************************************************************/

function allCommentsLinkCreate(data) {
    var html = '<a href="/api/v1/tweet/'+ data.comment['message_id'] +'/comment/">all comments</a>' ;

    var link = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('a').length

    deleteLastComment(data);
    createComment(data);

    if(!link) {
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').append(html);
    }
}

/****************************************************************************************/



Echo.private('comment')
    .listen('.newComment', (data) => {
        if(authUserId != data.user['user_id']) {

            var commentCount = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').length;

            if(commentCount <= 2) {
                createComment(data);
            }else {
                allCommentsLinkCreate(data);
            }
        }
    });
