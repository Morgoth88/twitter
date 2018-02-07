$(document).ready(function () {
    getTweets();
    setTimeout(function () {
        $('.alert-success').hide();
    }, 5000)
});


//display tweets
/******************************************************************************/
function getTweets(page = 1) {
    //ajax call
    /**************************************************************************/
    $.ajax({
        url: '/api/v1/tweet?page=' + page,
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        $('.panel-body').html('');

        //create messages foreach
        /**********************************************************************/
        for (let i in data.data) {
            //message data
            /******************************************************************/
            let userName = data.data[i].user['name'];
            let userId = data.data[i].user['id'];
            let userRole = data.data[i].user['role_id'];

            let messageId = data.data[i].id;
            let messageCreatedAt = data.data[i].created_at;
            let messageText = data.data[i].text;

            //generate message html
            /******************************************************************/
            let messageHtmlData = {
                messageId : messageId,
                messageText : messageText,
                messageCreatedAt : messageCreatedAt,
                messageUserName : generatePostUserName(userName, userId),
                userBanButton : generateUserBanButton(userRole, userId),
                messageBanButton : generateMessageBanButton(userRole, messageId),
                messageUpdateButton : generateMessageUpdateButton(userId, messageId, messageCreatedAt),
                messageDeleteButton : generateMessageDeleteButton(userId, messageId, messageCreatedAt)
            };

            $('.panel-body').append(generateMessageHtml(messageHtmlData));

            //create comments foreach
            /******************************************************************/
            for (let x in data.data[i].comment) {
                //in the case of the third loop, break foreach and
                // create all comments button
                /**************************************************************/
                if (x >= 3) {

                    let html = '<span class="allLink" onclick="allComments(' + messageId + ')">all comments</span>';
                    let commentsContainer = $('.tweet[data-id=' + messageId + ']').children('.comments-container');
                    let link = commentsContainer.children('.allLink').length;

                    if (!link) {
                        commentsContainer.append(html);
                    }
                    break;
                }

                // comment data
                /**************************************************************/
                let commentId = data.data[i].comment[x].id;
                let commentCreatedAt = data.data[i].comment[x].created_at;
                let commentText = data.data[i].comment[x].text;

                let commentUserNameData = data.data[i].comment[x].user['name'];
                let commentUserId = data.data[i].comment[x].user['id'];
                let commentUserRole = data.data[i].comment[x].user['role_id'];

                let tweet = $('.tweet[data-id=' + messageId + ']');

                //in the case of the comments-container not exist
                /**************************************************************/
                if (tweet.children('.comments-container').length < 1) {

                    tweet.append('<div class="comments-container"></div>')
                }

                //generate comment html
                /**************************************************************/
                let commentHtmlData = {
                    messageId : messageId,
                    commentId : commentId,
                    commentText : commentText,
                    commentCreatedAt : commentCreatedAt,
                    commentUserName :  generatePostUserName(commentUserNameData, commentUserId),
                    commentUserBanButton : generateUserBanButton(commentUserRole, commentUserId),
                    commentBanButton : generateCommentBanButton(commentUserRole, commentId),
                    commentUpdateButton : generateCommentUpdateButton(commentUserId, commentId, commentCreatedAt),
                    commentDeleteButton : generateCommentDeleteButton(commentUserId, commentId, commentCreatedAt)
                };

                let commentsContainer = tweet.children('.comments-container');
                commentsContainer.append(generateCommentHtml(commentHtmlData))

                //Add comments count
                /**************************************************************/
                let commentCount = data.data[i].comment.length;
                let commentCounter = (commentCount === 1) ? commentCount + ' comment' : commentCount + ' comments';

                tweet.children('.tweet-icons').children('.comment-count').text(commentCounter);
            }
            //comments foreach end
            /******************************************************************/
        }
        //messages foreach end
        /**********************************************************************/

        //add number of current page to panel body attribute
        /**********************************************************************/
        $('.panel-body').attr('data-page', data.current_page);

        //pagination
        //next page
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

        //pagination
        //previous page
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


//display all comments
/******************************************************************************/
function allComments(id) {

    //ajax call
    /**************************************************************************/
    $.ajax({
        url: '/api/v1/tweet/' + id + '/comment',
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        let tweet = $('.tweet[data-id=' + id + ']');
        let commentsContainer = $('.tweet[data-id=' + id + ']').children('.comments-container');

        //remove comments
        /**********************************************************************/
        commentsContainer.children('.comment').html('');

        //read page number from panel body attribute
        /**********************************************************************/
        let newPage = parseInt($('.panel-body').attr('data-page'));

        //change all comments button text and onclick function
        /**********************************************************************/
        commentsContainer.children('.allLink').text('hide');
        commentsContainer.children('.allLink').attr('onclick', 'getTweets(' + newPage + ')');

        //create comments foreach
        /**********************************************************************/
        for (let x in data) {

            // comment data
            /******************************************************************/
            let messageId = data[x].message_id;
            let commentId = data[x].id;
            let commentCreatedAt = data[x].created_at;
            let commentText = data[x].text;

            let commentUserNameData = data[x].user['name'];
            let commentUserId = data[x].user['id'];
            let commentUserRole = data[x].user['role_id'];

            //in the case of the comments-container not exist
            /**************************************************************/
            if (tweet.children('.comments-container').length < 1) {

                tweet.append('<div class="comments-container"></div>')
            }

            //generate comment html
            /******************************************************************/
            let commentHtmlData = {
                commentId : commentId,
                commentText : commentText,
                commentCreatedAt : commentCreatedAt,
                messageId : messageId,
                commentUserName : generatePostUserName(commentUserNameData, commentUserId),
                commentUserBanButton : generateUserBanButton(commentUserRole, commentId),
                commentBanButton : generateCommentBanButton(commentUserRole, commentId),
                commentUpdateButton : generateCommentUpdateButton(commentUserId, commentId, commentCreatedAt),
                commentDeleteButton : generateCommentDeleteButton(commentUserId, commentId, commentCreatedAt)
            };

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

        /*update & delete buttons will be removed after two minutes*/
        /**********************************************************************/
        hideButtons()
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


