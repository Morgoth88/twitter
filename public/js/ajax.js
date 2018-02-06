$(document).ready(function () {
    getTweets();
    setTimeout(function () {
        $('.alert-success').hide();
    },5000)
});


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
        /**********************************************************************************************************/
        for (let i in data.data) {

            let userName = data.data[i].user['name'];
            let userId = data.data[i].user['id'];
            let userRole = data.data[i].user['role_id'];

            let messageId = data.data[i].id;
            let messageCreatedAt = data.data[i].created_at;
            let messageText = data.data[i].text;

            let createdAt = new Date(messageCreatedAt);
            let date = new Date();
            let passedTime = (date.getTime() - createdAt.getTime()) / 1000 / 60;


            let messageUserName = (authUserRole === 0 )
                ? userName
                : '<a href="/api/v1/user/' + userId + '">' + userName + '</a>';


            let userBanButton = '';

            if ((authUserRole === 1)) {
                if (userRole === 0) {
                    {
                        userBanButton = '<button id="banUserBtn" onclick="banUser(' + userId + ')">' +
                            '<i class="fa fa-ban" aria-hidden="true"></i>' +
                            '</button>';
                    }
                }

            }

            let messageBanButton = '';

            if ((authUserRole === 1)) {
                if (userRole === 0) {
                    messageBanButton = '<button id="banMessBtn" onclick="banTweet(' + data.data[i].id + ')">' +
                        '<i class="fa fa-ban" aria-hidden="true"></i>' +
                        '</button>';
                }
            }

            let messageUpdateButton = (authUserId === userId && passedTime <= 2)
                ? '<button id="msgUpdtBtn" onclick="updateForm(' + data.data[i].id + ')">' +
                '<i class="fa fa-pencil" aria-hidden="true"></i>' +
                '</button>'
                : '';


            let messageDeleteButton = (authUserId === userId && passedTime <= 2)
                ? '<button id="msgDltBtn" onclick="deleteTweet(' + data.data[i].id + ')">' +
                '<i class="fa fa-times" aria-hidden="true"></i>' +
                '</button>'
                : '';

            let html =
                '<div class="tweet" data-id="' + messageId + '"> ' +
                '<div class="tweet-name">' + messageUserName + '' + userBanButton +
                '<span class="up-del-links">' + messageBanButton + messageUpdateButton + messageDeleteButton + '</span>' +
                '</div>' +
                '<div class="tweet-time-div">' +
                '<span class="tweet-time" data-time="' + messageCreatedAt + '"></span>' +
                '</div>' +
                '<div class="tweet-text" data-id="' + messageId + '">' + messageText + '</div>' +
                '<div class="tweet-icons">' +
                '<span class="comment-link">' +
                '<button id="cmntBtn" onclick="commentForm(' + messageId + ')">' +
                'new <i class="fa fa-comments" aria-hidden="true"></i>' +
                '</button>' +
                '</span>' +
                '<span class="comment-count"></span>' +
                '</div>' +
                '</div>';

            $('.panel-body').append(html);

            //comments
            /**********************************************************************************************************/
            for (let x in data.data[i].comment) {
                if (x >= 3) {

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

                let c = new Date(commentCreatedAt);
                let passedTime = (date.getTime() - c.getTime()) / 1000 / 60;


                let commentUserName = (authUserRole === 0 )
                    ? commentUserNameData
                    : '<a href="/api/v1/user/' + commentUserId + '">' + commentUserNameData + '</a>';

                let commentUserBanButton = '';

                if ((authUserRole === 1)) {
                    if (commentUserRole === 0) {
                        commentUserBanButton = '<button id="banUserBtn"' +
                            ' onclick="banUser(' + commentUserId + ')">' +
                            '<i class="fa fa-ban" aria-hidden="true"></i>' +
                            '</button>';
                    }
                }

                let commentBanButton = '';

                if ((authUserRole === 1)) {
                    if (commentUserRole === 0) {
                        commentBanButton = '<button id="banCommBtn" onclick="banComment(' + commentId + ')">' +
                            '<i class="fa fa-ban" aria-hidden="true"></i>' +
                            '</button>';
                    }
                }

                let commentUpdateButton = (authUserId === commentUserId && passedTime <= 2)
                    ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + data.data[i].comment[x].id + ')">' +
                    '<i class="fa fa-pencil" aria-hidden="true"></i>' +
                    '</button>'
                    : '';


                let commentDeleteButton = (authUserId === commentUserId && passedTime <= 2)
                    ? '<button id="msgDltBtn" onclick="deleteComment(' + commentId + ')">' +
                    '<i class="fa fa-times" aria-hidden="true"></i>' +
                    '</button>'
                    : '';

                let tweet = $('.tweet[data-id=' + messageId + ']');

                if (tweet.children('.comments-container').length < 1) {

                    tweet.append('<div class="comments-container"></div>')
                }

                let CmntHtml =
                    '<div class="comment" data-id="' + commentId + '"> ' +
                    '<div class="comment-name">' + commentUserName + '' + commentUserBanButton +
                    '<span class="up-del-links">' + commentBanButton + commentUpdateButton + commentDeleteButton + '</span>' +
                    '<span class="comment-time" data-time ="' + commentCreatedAt + '"></span>' +
                    '</div>' +
                    '<div class="comment-text" data-comment-id="' + commentId + '" data-tweet-id="' + messageId + '">' +
                    commentText + '</div>' +
                    '</div>';


                let commentsContainer = tweet.children('.comments-container');

                commentsContainer.append(CmntHtml);

                let commentCount = data.data[i].comment.length;
                let commentCounter = (commentCount === 1) ? commentCount + ' comment' : commentCount + ' comments';

                tweet.children('.tweet-icons').children('.comment-count').text(commentCounter);
            }
        }
        //foreach end
        /************************************************************************************************************/


        $('.panel-body').attr('data-page', data.current_page);


        /*pagination*/
        /************************************************************************************************************/
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
        /************************************************************************************************************/


        /*tweets & comment created at time update every minute*/
        /************************************************************************************************************/

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
        /*******************************************************************************************************************/


        /*update & delete buttons will be removed after two minutes*/
        /*******************************************************************************************************************/
        setTimeout(function () {
            $('.up-del-links').each(function () {
                $(this).children('#msgDltBtn').hide();
                $(this).children('#msgUpdtBtn').hide();
            })
        }, 120000)
        /*******************************************************************************************************************/

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

        newPage = parseInt($('.panel-body').attr('data-page'));

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

            let date = new Date();
            let createdAt = new Date(commentCreatedAt);
            let passedTime = (date.getTime() - createdAt.getTime()) / 1000 / 60;


            let commentUserName = (authUserRole === 0 )
                ? commentUserNameData
                : '<a href="/api/v1/user/' + commentUserId + '">' + commentUserNameData + '</a>';

            let commentUserBanButton = '';

            if ((authUserRole === 1)) {
                if (commentUserRole === 0) {
                    commentUserBanButton = '<button id="banUserBtn"' +
                        ' onclick="banUser(' + commentUserId + ')">' +
                        '<i class="fa fa-ban" aria-hidden="true"></i>' +
                        '</button>';
                }
            }

            let commentBanButton = '';

            if ((authUserRole === 1)) {
                if (commentUserRole === 0) {
                    commentBanButton = '<button id="banCommBtn" onclick="banComment(' + commentId + ')">' +
                        '<i class="fa fa-ban" aria-hidden="true"></i>' +
                        '</button>';
                }
            }

            let commentUpdateButton = (authUserId === commentUserId && passedTime <= 2)
                ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + commentId + ')">' +
                '<i class="fa fa-pencil" aria-hidden="true"></i>' +
                '</button>'
                : '';


            let commentDeleteButton = (authUserId === commentUserId && passedTime <= 2)
                ? '<button id="msgDltBtn" onclick="deleteComment(' + commentId + ')">' +
                '<i class="fa fa-times" aria-hidden="true"></i>' +
                '</button>'
                : '';

            let commentsContainer = tweet.children('.comments-container');

            if (commentsContainer.length < 1) {

                tweet.append('<div class="comments-container"></div>')
            }

            let commentHtml =
                '<div class="comment" data-id="' + commentId + '"> ' +
                '<div class="comment-name">' + commentUserName + '' + commentUserBanButton +
                '<span class="up-del-links">' + commentBanButton + commentUpdateButton + commentDeleteButton + '</span>' +
                '<span class="comment-time" data-time ="' + commentCreatedAt + '"></span>' +
                '</div>' +
                '<div class="comment-text" data-comment-id="' + commentId + '" data-tweet-id="' + messageId + '">' +
                commentText + '</div>' +
                '</div>';


            commentsContainer.prepend(commentHtml);

            let commentCount = commentsContainer.children('.comment').length;
            let commentCounter = (commentCount === 1) ? commentCount + ' comment' : commentCount + ' comments';

            tweet.children('.tweet-icons').children('.comment-count').text(commentCounter);
        }
        /*tweets & comment created at time update every minute*/
        /************************************************************************************************************/

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
        /*******************************************************************************************************************/


        /*update & delete buttons will be removed after two minutes*/
        /*******************************************************************************************************************/
        setTimeout(function () {
            $('.up-del-links').each(function () {
                $(this).children('#msgDltBtn').hide();
                $(this).children('#msgUpdtBtn').hide();
            })
        }, 120000)
        /*******************************************************************************************************************/

    });
}

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
            'X-CSRF-TOKEN':csrfToken
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

    var messageId = $('.comment[data-id=' + commentId + ']').parent('.comments-container').parent('.tweet').attr('data-id');

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


