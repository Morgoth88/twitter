//generators
/******************************************************************************/
/******************************************************************************/
function passedTime(messageCreatedAt) {
    let createdAt = new Date(messageCreatedAt);
    let date = new Date();
    return (date.getTime() - createdAt.getTime()) / 1000 / 60;
}

function generatePostUserName(userName, userId) {

    let messageUserName = (authUserRole === 0 )
        ? userName
        : '<a href="/api/v1/user/' + userId + '">' + userName + '</a>';

    return messageUserName;
}

function generateUserBanButton(userRole, userId) {

    let userBanButton = '';

    if ((authUserRole === 1)) {
        if (userRole === 0) {
            userBanButton = '<button id="banUserBtn" onclick="banUser(' + userId + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }

    return userBanButton;
}

function generateMessageBanButton(userRole, messageId) {

    let messageBanButton = '';

    if ((authUserRole === 1)) {
        if (userRole === 0) {
            messageBanButton = '<button id="banMessBtn" onclick="banTweet(' + messageId + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }
    return messageBanButton;
}

function generateCommentBanButton(commentUserRole, commentId) {

    let commentBanButton = '';

    if ((authUserRole === 1)) {
        if (commentUserRole === 0) {
            commentBanButton = '<button id="banCommBtn" onclick="banComment(' + commentId + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }
    return commentBanButton;
}

function generateMessageUpdateButton(userId, messageId, messageCreatedAt) {

    let messageUpdateButton = (authUserId === userId && passedTime(messageCreatedAt) <= 2)
        ? '<button id="msgUpdtBtn" onclick="updateForm(' + messageId + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';

    return messageUpdateButton;
}

function generateCommentUpdateButton(commentUserId, commentId, commentCreatedAt) {

    let commentUpdateButton = (authUserId === commentUserId && passedTime(commentCreatedAt) <= 2)
        ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + commentId + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';

    return commentUpdateButton;
}

function generateMessageDeleteButton(userId, messageId, messageCreatedAt) {

    let messageDeleteButton = (authUserId === userId && passedTime(messageCreatedAt) <= 2)
        ? '<button id="msgDltBtn" onclick="deleteTweet(' + messageId + ')">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>'
        : '';
    return messageDeleteButton;
}

function generateCommentDeleteButton(commentUserId, commentId, commentCreatedAt) {

    let commentDeleteButton = (authUserId === commentUserId && passedTime(commentCreatedAt) <= 2)
        ? '<button id="msgDltBtn" onclick="deleteComment(' + commentId + ')">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>'
        : '';
    return commentDeleteButton;
}

//html generators
/******************************************************************************/
function generateMessageHtml(data) {
    return '<div class="tweet" data-id="' + data.messageId + '"> ' +
        '<div class="tweet-name">' + data.messageUserName + '' + data.userBanButton +
        '<span class="up-del-links">' + data.messageBanButton + data.messageUpdateButton + data.messageDeleteButton + '</span>' +
        '</div>' +
        '<div class="tweet-time-div">' +
        '<span class="tweet-time" data-time="' + data.messageCreatedAt + '"></span>' +
        '</div>' +
        '<div class="tweet-text" data-id="' + data.messageId + '">' + data.messageText + '</div>' +
        '<div class="tweet-icons">' +
        '<span class="comment-link">' +
        '<button id="cmntBtn" onclick="commentForm(' + data.messageId + ')">' +
        'new <i class="fa fa-comments" aria-hidden="true"></i>' +
        '</button>' +
        '</span>' +
        '<span class="comment-count"></span>' +
        '</div>' +
        '</div>';

}

function generateCommentHtml(data) {
    return '<div class="comment" data-id="' + data.commentId + '"> ' +
        '<div class="comment-name">' + data.commentUserName + '' + data.commentUserBanButton +
        '<span class="up-del-links">' + data.commentBanButton + data.commentUpdateButton + data.commentDeleteButton + '</span>' +
        '<span class="comment-time" data-time ="' + data.commentCreatedAt + '"></span>' +
        '</div>' +
        '<div class="comment-text" data-comment-id="' + data.commentId + '" data-tweet-id="' + data.messageId + '">' +
        data.commentText + '</div>' +
        '</div>';
}


//time generator
/******************************************************************************/
function generateTime() {

    $('.tweet-time').each(function () {
        let time = $(this).attr('data-time');
        $(this).text(moment(time).fromNow());
    });

    $('.comment-time').each(function () {
        let time = $(this).attr('data-time');
        $(this).text(moment(time).fromNow());
    });

    setInterval(function () {
        $('.tweet-time').each(function () {
            let time = $(this).attr('data-time');
            $(this).text(moment(time).fromNow());
        });
        $('.comment-time').each(function () {
            let time = $(this).attr('data-time');
            $(this).text(moment(time).fromNow());
        });
    }, 60000);
}

//hide buttons after 2 minutes
/******************************************************************************/
function hideButtons() {
    setTimeout(function () {
        $('.up-del-links').each(function () {
            $(this).children('#msgDltBtn').hide();
            $(this).children('#msgUpdtBtn').hide();
        })
    }, 120000)
}

