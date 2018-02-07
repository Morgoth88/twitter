$(document).ready(function () {
    getTweets();
    setTimeout(function () {
        $('.alert-success').hide();
    }, 5000)
});



//display tweets
/******************************************************************************/
function getTweets(page = 1) {

    $.ajax({
        url: '/api/v1/tweet?page=' + page,
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        $('.tweet').each(function () {
            $(this).remove();
        });

        //messages
        /**********************************************************************/
        for (let i in data.data) {

            let userName = data.data[i].user['name'];
            let userId = data.data[i].user['id'];
            let userRole = data.data[i].user['role_id'];

            let messageId = data.data[i].id;
            let messageCreatedAt = data.data[i].created_at;
            let messageText = data.data[i].text;

            //generate tweet html
            /******************************************************************/
            let messageUserName = generatePostUserName(userName, userId);
            let userBanButton = generateUserBanButton(userRole, userId);
            let messageBanButton = generateMessageBanButton(userRole, messageId);
            let messageUpdateButton = generateMessageUpdateButton(userId, messageId, messageCreatedAt);
            let messageDeleteButton = generateMessageDeleteButton(userId, messageId, messageCreatedAt);

            let messageHtmlData = {
                messageId : messageId,
                messageText : messageText,
                messageCreatedAt : messageCreatedAt,
                messageUserName : messageUserName,
                userBanButton : userBanButton,
                messageBanButton : messageBanButton,
                messageUpdateButton : messageUpdateButton,
                messageDeleteButton : messageDeleteButton
            };

            $('.panel-body').append(generateMessageHtml(messageHtmlData));

            //comments
            /******************************************************************/
            for (let x in data.data[i].comment) {
                if (x >= 3) {
                    //in the case of the third loop, break foreach and
                    // create all comments button
                    /**********************************************************/
                    let html = '<span class="allLink" onclick="allComments(' + messageId + ')">all comments</span>';

                    let commentsContainer = $('.tweet[data-id=' + messageId + ']').children('.comments-container');

                    let link = commentsContainer.children('.allLink').length;

                    if (!link) {
                        commentsContainer.append(html);
                    }
                    break;
                }

                let commentId = data.data[i].comment[x].id;
                let commentCreatedAt = data.data[i].comment[x].created_at;
                let commentText = data.data[i].comment[x].text;

                let commentUserNameData = data.data[i].comment[x].user['name'];
                let commentUserId = data.data[i].comment[x].user['id'];
                let commentUserRole = data.data[i].comment[x].user['role_id'];

                //generate comment html
                /**************************************************************/
                let commentUserName = generatePostUserName(commentUserNameData, commentUserId);
                let commentUserBanButton = generateUserBanButton(commentUserRole, commentUserId);
                let commentBanButton = generateCommentBanButton(commentUserRole, commentId);
                let commentUpdateButton = generateCommentUpdateButton(commentUserId, commentId, commentCreatedAt);
                let commentDeleteButton = generateCommentDeleteButton(commentUserId, commentId, commentCreatedAt);

                let tweet = $('.tweet[data-id=' + messageId + ']');

                //in the case of the comments-container not exist
                /**************************************************************/
                if (tweet.children('.comments-container').length < 1) {

                    tweet.append('<div class="comments-container"></div>')
                }

                let commentHtmlData = {
                    messageId : messageId,
                    commentId : commentId,
                    commentText : commentText,
                    commentCreatedAt : commentCreatedAt,
                    commentUserName : commentUserName,
                    commentUserBanButton : commentUserBanButton,
                    commentBanButton : commentBanButton,
                    commentUpdateButton : commentUpdateButton,
                    commentDeleteButton : commentDeleteButton
                };


                //Add comments count
                /**************************************************************/
                let commentsContainer = tweet.children('.comments-container');
                commentsContainer.append(generateCommentHtml(commentHtmlData));

                let commentCount = data.data[i].comment.length;
                let commentCounter = (commentCount === 1) ? commentCount + ' comment' : commentCount + ' comments';

                tweet.children('.tweet-icons').children('.comment-count').text(commentCounter);
            }
        }
        //foreach end
        /**********************************************************************/

        $('.panel-body').attr('data-page', data.current_page);

        /*pagination*/
        /**********************************************************************/
        /**********************************************************************/

        // next page
        /**********************************************************************/
        if (data.last_page <= data.current_page) {
            $('#next').remove();
        }
        else {
            let newPage = data.current_page + 1;

            if ($('#next').length === 0) {
                let next = $('<button id="next" onclick="getTweets(' + newPage + ')">next</button>');
                $('.pagination_buttons').append(next);
            }
            else {
                $('#next').attr('onclick', 'getTweets(' + newPage + ')')
            }
        }

        // previous page
        /**********************************************************************/
        if (data.current_page > 1) {

            let newPage = data.current_page - 1;

            if ($('#previous').length === 0) {
                let previous = $('<button id="previous" onclick="getTweets(' + newPage + ')">previous</button>');
                $('.pagination_buttons').prepend(previous);
            }
            else {
                $('#previous').attr('onclick', 'getTweets(' + newPage + ')')
            }
        }
        else {
            $('#previous').remove();
        }

        /*tweets & comment created at time update every minute*/
        /**********************************************************************/
        generateTime();

        /*update & delete buttons will be removed after two minutes*/
        /**********************************************************************/
        hideButtons();

    });
}


function allComments(id) {

    $.ajax({
        url: '/api/v1/tweet/' + id + '/comment',
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        let tweet = $('.tweet[data-id=' + id + ']');
        let commentsContainer = $('.tweet[data-id=' + id + ']').children('.comments-container');

        commentsContainer.children('.comment').each(function () {
            $(this).remove();
        });

        //read page number from attribute
        let newPage = parseInt($('.panel-body').attr('data-page'));

        //change all comments button text
        /**********************************************************************/
        commentsContainer.children('.allLink').text('hide');
        commentsContainer.children('.allLink').attr('onclick', 'getTweets(' + newPage + ')');

        for (let x in data) {

            let messageId = data[x].message_id;
            let commentId = data[x].id;
            let commentCreatedAt = data[x].created_at;
            let commentText = data[x].text;

            let commentUserNameData = data[x].user['name'];
            let commentUserId = data[x].user['id'];
            let commentUserRole = data[x].user['role_id'];

            //generate comment html
            /******************************************************************/
            let commentUserName = generatePostUserName(commentUserNameData, commentUserId);
            let commentUserBanButton = generateUserBanButton(commentUserRole, commentId);
            let commentBanButton = generateCommentBanButton(commentUserRole, commentId);
            let commentUpdateButton = generateCommentUpdateButton(commentUserId, commentId, commentCreatedAt);
            let commentDeleteButton = generateCommentDeleteButton(commentUserId, commentId, commentCreatedAt);

            //in the case of the comments-container not exist
            /**************************************************************/
            if (tweet.children('.comments-container').length < 1) {

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

            //Add html to container
            /******************************************************************/
            let commentsContainer = tweet.children('.comments-container');
            commentsContainer.prepend(generateCommentHtml(commentHtmlData));

            //Add comments count
            /******************************************************************/
            let commentCount = commentsContainer.children('.comment').length;
            let commentCounter = (commentCount === 1) ? commentCount + ' comment' : commentCount + ' comments';

            tweet.children('.tweet-icons').children('.comment-count').text(commentCounter);
        }
        /*tweets & comment created at time update every minute*/
        /**********************************************************************/
        generateTime();
        /**********************************************************************/

        /*update & delete buttons will be removed after two minutes*/
        /**********************************************************************/
        hideButtons()
        /**********************************************************************/

    });
}


//ajax calls
/******************************************************************************/
/******************************************************************************/
var csrfToken = $('meta[name="csrf-token"]').attr('content');

function deleteTweet(id) {
    $.ajax({
        url: '/api/v1/tweet/' + id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
    })
}


function banTweet(id) {
    $.ajax({
        url: '/api/v1/ban/message/' + id,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
    })
}

function banUser(id) {
    $.ajax({
        url: '/api/v1/ban/user/' + id,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
    })
}

function deleteComment(commentId) {

    let messageId = $('.comment[data-id=' + commentId + ']').parent('.comments-container').parent('.tweet').attr('data-id');

    $.ajax({
        url: '/api/v1/tweet/' + messageId + '/comment/' + commentId,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
    })
}


function banComment(commentId) {

    let messageId = $('.comment[data-id=' + commentId + ']').parent('.comments-container').parent('.tweet').attr('data-id');

    $.ajax({
        url: '/api/v1/ban/message/' + messageId + '/comment/' + commentId,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
    })
}


