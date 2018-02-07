function wsCreateComment(data) {

    let userId = data.user['user_id'];
    let userName = data.user['userName'];
    let userRole = data.user['userRole'];

    let commentId = data.comment['id'];
    let commentCreatedAt = data.comment['created_at'].date;
    let commentText = data.comment['text'];

    let messageId = data.comment['message_id'];


    let commentUserName = generatePostUserName(userName, userId);
    let commentUserBanButton = generateUserBanButton(userRole, userId);
    let commentBanButton = generateCommentBanButton(userRole, commentId);
    let commentUpdateButton = generateCommentUpdateButton(userId, commentId, commentCreatedAt);
    let commentDeleteButton = generateCommentDeleteButton(userId, commentId, commentCreatedAt);

    let tweet = $('.tweet[data-id=' + data.comment['message_id'] + ']');

    if (!tweet.children('.comments-container').length) {

        tweet.append('<div class="comments-container"></div>')
    }

    let commentHtmlData = {
        commentId : commentId,
        commentText : commentText,
        commentCreatedAt : commentCreatedAt,
        messageId : messageId,
        commentUserName : commentUserName,
        commentUserBanButton : commentUserBanButton,
        commentBanButton : commentBanButton,
        commentUpdateButton : commentUpdateButton,
        commentDeleteButton : commentDeleteButton
    };

    let commentHtml = generateCommentHtml(commentHtmlData);

    tweet.children('.comments-container').prepend(commentHtml);

    let commentCount = data.commentsCount;
    let commentCounter = (commentCount === 1)
        ? commentCount + ' comment'
        : commentCount + ' comments';

    let tweetCommentCounter = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.tweet-icons').children('.comment-count');

    tweetCommentCounter.show();
    tweetCommentCounter.text(commentCounter);

    //comment created at time
    /**************************************************************************/
    let commentTimeSpan = $('.comment[data-id=' + commentId + ']').children('.comment-name').children('.comment-time');
    let commentTime = commentTimeSpan.attr('data-time');
    commentTimeSpan.text(moment(commentTime).fromNow());

    /*update & delete buttons will be removed after two minutes*/
    /**************************************************************************/
    hideButtons();
}

/******************************************************************************/


function deleteLastComment(data) {
    let lastComment = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2);
    lastComment.hide();
}


/******************************************************************************/

function allCommentsLinkCreate(data) {
    let commentsContainer = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container');

    let html = '<span class="allLink" onclick="allComments(' + data.comment['message_id'] + ')">all comments</span>';
    let link = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.allLink').length;

    deleteLastComment(data);
    wsCreateComment(data);

    if (!link) {
        commentsContainer.append(html);
    }
}


Echo.private('comment')
    .listen('.commentCreated', (data) => {

        let commentsContainer = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container');
        let commentCount = commentsContainer.children('.comment').length;

        if (commentsContainer.children('.allLink').text() === 'hide') {
            wsCreateComment(data);
        }
        else if (commentCount > 2 && commentsContainer.children('.allLink').text() !== 'hide') {
            allCommentsLinkCreate(data);
        }
        else {
            wsCreateComment(data);
        }
    });


/******************************************************************************/
/******************************************************************************/

function wsDeleteComment(data) {
    let comment = $('.comment[data-id=' + data.comment['id'] + ']');
    comment.remove();

    let commentCount = data.commentsCount - 1;
    let commentCounter = (commentCount === 1)
        ? commentCount + ' comment'
        : commentCount + ' comments';

    let tweet = $('.tweet[data-id=' + data.comment['message_id'] + ']');
    let commentsContainer = tweet.children('.comments-container');
    let commentsCountSpan = tweet.children('.tweet-icons').children('.comment-count');

    commentsCountSpan.text(commentCounter);
    commentsContainer.children('.comment').eq(2).show();

    if (commentCount === 0) {
        commentsContainer.remove();
        commentsCountSpan.hide();
    }
    else if (commentCount < 4) {
        commentsContainer.children('.allLink').hide();
    }
}


Echo.private('commentDelete')
    .listen('.commentDeleted', (data) => {
        wsDeleteComment(data);
    });


Echo.private('commentBanned')
    .listen('.commentBanned', (data) => {
        wsDeleteComment(data);
    });


/******************************************************************************/
/******************************************************************************/


function wsUpdateComment(data) {

    let oldComment = $('.comment[data-id=' + data.comment['old_id'] + ']');

    let commentId = data.comment['id'];

    /*change tweet id and text*/
    let commentText = oldComment.children('.comment-text');
    commentText.attr('data-comment-id', commentId);
    commentText.text(data.comment['text']);

    oldComment.attr('data-id', commentId);

    let newComment = $('.comment[data-id=' + commentId + ']');
    let commentLinks = newComment.children('.comment-name').children('.up-del-links');

    /*change message btn route to actual id*/
    commentLinks.children('#banCommBtn').attr('onclick', 'banComment(' + commentId + ')');

    /*change update message btn to actual id*/
    commentLinks.children('#msgUpdtBtn').attr('onclick', 'commentUpdateForm(' + commentId + ')');

    /*change delete message btn form action route to actual id*/
    commentLinks.children('#msgDltBtn').attr('onclick', 'deleteComment(' + commentId + ')');
}


Echo.private('commentUpdate')
    .listen('.commentUpdated', (data) => {
        wsUpdateComment(data);
    });
