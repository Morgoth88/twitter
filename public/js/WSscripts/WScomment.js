var csrfToken = $('meta[name=csrf-token]').attr('content');

function createComment(data) {

    var userName = (authUserRole == 0 )
        ? data.user['userName']
        : '<a href="/api/v1/user/' + data.user['user_id'] + '">' + data.user['userName'] + '</a>';



    if((authUserRole == 0)) {
        var banBtn = '';
    }
    else
    {
        if (data.user['userRole'] == 1) {
            var banBtn = '';
        }
        else
        {
            var banBtn =  '<button id="banUserBtn" onclick="banUser(' + data.user['user_id'] + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }


    if((authUserRole == 0)) {
        var msgBan = '';
    }
    else
    {
        if (data.user['userRole']  == 1) {
            var msgBan = '';
        }
        else
        {
            var msgBan =  '<button id="banCommBtn" onclick="banCmnt(' + data.comment['id'] + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }


    var updtBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + data.comment['id'] + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';


    var dltBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgDltBtn" onclick="deleteCmnt(' + data.comment['id'] + ')">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>'
        : '';

    if ($('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').length < 1) {

        $('.tweet[data-id=' + data.comment['message_id'] + ']').append('<div class="comments-container"></div>')
    }

    var html =
        '<div class="comment" data-id="' + data.comment['id'] + '"> ' +
        '<div class="comment-name">' + userName + '' + banBtn +
        '<span class="up-del-links">' + msgBan + updtBtn + dltBtn + '</span>' +
        '<span class="comment-time" data-time="' + data.comment['created_at'].date + '"></span>' +
        '</div>' +
        '<div class="comment-text" data-comment-id="' + data.comment['id'] + '" data-tweet-id="' + data.comment['message_id'] + '">' +
        data.comment['text'] + '</div>' +
        '</div>';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').prepend(html);

    var commentCount = data.commentsCount;
    var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').show();
    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);

    $('.tweet-time').each(function () {
        var time = $(this).attr('data-time');
        $(this).text(moment(time).fromNow());
    });
    $('.comment-time').each(function () {
        var time = $(this).attr('data-time');
        $(this).text(moment(time).fromNow());
    });

    setInterval(function () {
        $('.tweet-time').each(function () {
            var time = $(this).attr('data-time');
            $(this).text(moment(time).fromNow());
        });
        $('.comment-time').each(function () {
            var time = $(this).attr('data-time');
            $(this).text(moment(time).fromNow());
        });
    }, 60000);

    setTimeout(function () {
        $('.comment[data-id='+  data.comment['id'] +']').children('.comment-name').children('.up-del-links').children('#msgDltBtn').hide();
        $('.comment[data-id='+  data.comment['id'] +']').children('.comment-name').children('.up-del-links').children('#msgUpdtBtn').hide();
    },120000)

}
/****************************************************************************************/


function deleteLastComment(data) {
    var lastComment = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2);
    lastComment.hide();
}

/****************************************************************************************/

function allCommentsLinkCreate(data) {
    var html = '<span class="allLink" onclick="allComments('+  data.comment['message_id'] +')">all comments</span>' ;

    var link = $('.tweet[data-id='+  data.comment['message_id'] +']').children('.comments-container').children('.allLink').length

    deleteLastComment(data);
    createComment(data);

    if(!link) {
        $('.tweet[data-id='+  data.comment['message_id'] +']').children('.comments-container').append(html);
    }
}

/****************************************************************************************/

Echo.private('comment')
    .listen('.newComment', (data) => {
        var commentCount = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').length;

        if($('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.allLink').text() == 'hide'  ){
            createComment(data);
        }
        else if(commentCount > 2  && $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.allLink').text() != 'hide'){
            allCommentsLinkCreate(data);
        }
        else {
            createComment(data);
        }
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/

function deleteComment(data) {
    var comment = $('.comment[data-id='+ data.comment['id'] +']');
    comment.remove();

    var commentCount = data.commentsCount - 1;
    var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);

     $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2).show();

    if(commentCount == 0){
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').remove();
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').hide();
    }
    else if(commentCount < 4 ){
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.allLink').hide();
    }
}


Echo.private('commentDelete')
    .listen('.cmntDel', (data) => {
        deleteComment(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/


function banComment(data) {
    var comment = $('.comment[data-id='+ data.comment['id'] +']');
    comment.remove();

    var commentCount = data.commentsCount;
    var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

    $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);

    var lastComment = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2);
    lastComment.show();


    if(commentCount == 0){
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').remove();
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count').hide();
    }
    else if(commentCount < 4 ){
        $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.allLink').hide();
    }
}


Echo.private('commentBanned')
    .listen('.cmntBan', (data) => {
        banComment(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/



var csrfToken = $('meta[name=csrf-token]').attr('content');


function updateComment(data) {

    var oldcomment = $('.comment[data-id=' + data.comment['old_id'] + ']');

    /*change tweet id and text*/
    var commentText = oldcomment.children('.comment-text');
    commentText.attr('data-comment-id', data.comment['id']);
    commentText.text(data.comment['text']);

    oldcomment.attr('data-id', data.comment['id']);

    var newcomment = $('.comment[data-id=' + data.comment['id'] + ']');

    /*change message btn route to actual id*/
    newcomment.children('.comment-name').children('.up-del-links')
        .children('#banCommBtn').attr('onclick','banComment('+ data.comment['id'] +')');

    /*change update message btn to actual id*/
    newcomment.children('.comment-name').children('.up-del-links')
        .children('#msgUpdtBtn').attr('onclick', 'commentUpdateForm(' + data.comment['id'] + ')');

    /*change delete message btn form action route to actual id*/
    newcomment.children('.comment-name').children('.up-del-links')
        .children('#msgDltBtn').attr('onclick','deleteCmnt(' + data.comment['id'] +')');
}

/****************************************************************************************/


Echo.private('commentUpdate')
    .listen('.cmntUpdt', (data) => {
        updateComment(data);
    });
