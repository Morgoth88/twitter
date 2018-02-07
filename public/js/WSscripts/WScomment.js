function wsCreateComment(data) {

    // comment data
    /**************************************************************************/
    let userId = data.user['user_id'];
    let userName = data.user['userName'];
    let userRole = data.user['userRole'];

    let commentId = data.comment['id'];
    let commentCreatedAt = data.comment['created_at'].date;
    let commentText = data.comment['text'];

    let messageId = data.comment['message_id'];

    let tweet = $('.tweet[data-id=' + data.comment['message_id'] + ']');

    //in the case of the comments-container not exist
    /**************************************************************************/
    if (!tweet.children('.comments-container').length) {

        tweet.append('<div class="comments-container"></div>')
    }

    //generate comment html
    /**************************************************************************/
    let commentHtmlData = {
        commentId : commentId,
        commentText : commentText,
        commentCreatedAt : commentCreatedAt,
        messageId : messageId,
        commentUserName : generatePostUserName(userName, userId),
        commentUserBanButton : generateUserBanButton(userRole, userId),
        commentBanButton : generateCommentBanButton(userRole, commentId),
        commentUpdateButton : generateCommentUpdateButton(userId, commentId, commentCreatedAt),
        commentDeleteButton : generateCommentDeleteButton(userId, commentId, commentCreatedAt)
    };

    tweet.children('.comments-container').prepend(generateCommentHtml(commentHtmlData));

    //Add comments count
    /**************************************************************************/
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



function deleteLastComment(data) {
    let lastComment = $('.tweet[data-id=' + data.comment['message_id'] + ']').children('.comments-container').children('.comment').eq(2);
    lastComment.hide();
}



function allCommentsLinkCreate(data) {

    let tweet = $('.tweet[data-id=' + data.comment['message_id'] + ']');
    tweet.children('.tweet-icons').children('.comment-count').attr('onclick','allComments(' + data.comment['message_id'] + ')');

    deleteLastComment(data);
    wsCreateComment(data);
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

    let newCommentId = data.comment['id'];

    /*change tweet id and text*/
    let commentText = oldComment.children('.comment-text');
    commentText.attr('data-comment-id', newCommentId);
    commentText.text(data.comment['text']);

    oldComment.attr('data-id', newCommentId);

    let newComment = $('.comment[data-id=' + newCommentId + ']');
    let newCommentLinks = newComment.children('.comment-name').children('.up-del-links');

    /*change message btn route to actual id*/
    newCommentLinks.children('#banCommBtn').attr('onclick', 'banComment(' + newCommentId + ')');

    /*change update message btn to actual id*/
    newCommentLinks.children('#msgUpdtBtn').attr('onclick', 'commentUpdateForm(' + newCommentId + ')');

    /*change delete message btn form action route to actual id*/
    newCommentLinks.children('#msgDltBtn').attr('onclick', 'deleteComment(' + newCommentId + ')');
}


Echo.private('commentUpdate')
    .listen('.commentUpdated', (data) => {
        wsUpdateComment(data);
    });
