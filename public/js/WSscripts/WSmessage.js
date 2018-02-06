function wsCreateTweet(data) {

    let userId = data.user['user_id'];
    let userName = data.user['userName'];
    let userRole = data.user['userRole'];

    let messageId = data.message['id'];
    let createdAt = data.message['created_at'].date;
    let messageText = data.message['text'];

    let messageUserName = (authUserRole === 0 )
        ? userName
        : '<a href="/api/v1/user/' + userId + '">' + userName + '</a>';

    let userBanButton = '';

    if ((authUserRole === 1)) {
        if (userRole === 0) {
            userBanButton = '<button id="banUserBtn" onclick="banUser(' + userId + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }

    let messageBanButton = '';

    if ((authUserRole === 1)) {
        if (userRole === 0) {
            messageBanButton = '<button id="banMessBtn"' +
                ' onclick="banTweet(' + messageId + ')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }


    let messageUpdateButton = (authUserId === userId)
        ? '<button id="msgUpdtBtn" onclick="updateForm(' + messageId + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';


    let messageDeleteButton = (authUserId === userId)
        ? '<button id="msgDltBtn" onclick="deleteTweet(' + messageId + ')">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>'
        : '';


    let html =
        '<div class="tweet" data-id="' + messageId + '"> ' +
        '<div class="tweet-name">' + messageUserName + '' + userBanButton +
        '<span class="up-del-links">' + messageBanButton + messageUpdateButton + messageDeleteButton + '</span>' +
        '</div>' +
        '<div class="tweet-time-div">' +
        '<span class="tweet-time" data-time="' + createdAt + '"></span>' +
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


    $('.panel-body').prepend(html);


    /*pagination*/
    /****************************************************************************************/
    if ($('.tweet').length < 6) {
        $('#next').remove();
    }
    else {
        $('.tweet').eq(5).remove();

        let newPage = parseInt($('.panel-body').attr('data-page')) + 1;

        if ($('#next').length === 0) {
            let next = $('<button id="next" onclick="getTweets(' + newPage + ')">next</button>');

            $('.pagination_buttons').append(next);
        }
    }

    //message created at time
    /****************************************************************************************/


    let messageTimeSpan = $('.tweet[data-id=' + messageId + ']').children('.tweet-time-div').children('.tweet-time');
    let messageTime = messageTimeSpan.attr('data-time');

    messageTimeSpan.text(moment(messageTime).fromNow());


    setTimeout(function () {
        let tweetLinks = $('.tweet[data-id=' + data.message['id'] + ']').children('.tweet-name').children('.up-del-links');
        tweetLinks.children('#msgDltBtn').hide();
        tweetLinks.children('#msgUpdtBtn').hide();
    }, 120000)
}


Echo.private('message')
    .listen('.messageCreated', (data) => {
        wsCreateTweet(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/


function wsDeleteMessage(data) {

    let tweet = $('.tweet[data-id=' + data.message['id'] + ']');
    tweet.remove();
}

Echo.private('messageDelete')
    .listen('.messageDeleted', (data) => {
        wsDeleteMessage(data);
    });


Echo.private('messageBanned')
    .listen('.messageBanned', (data) => {
        wsDeleteMessage(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/


function wsUpdateTweet(data) {

    let oldTweet = $('.tweet[data-id=' + data.message['old_id'] + ']');

    let messageId = data.message['id'];

    /*change tweet id and text*/
    let tweetText = oldTweet.children('.tweet-text');
    tweetText.attr('data-id', messageId);
    tweetText.text(data.message['text']);

    oldTweet.attr('data-id', messageId);

    let newTweet = $('.tweet[data-id=' + messageId + ']');

    let newTweetLinks = newTweet.children('.tweet-name').children('.up-del-links');

    /*change message btn route to actual id*/
    newTweetLinks.children('#banMessBtn').attr('onclick', 'banTweet(' + messageId + ')');

    /*change update message btn to actual id*/
    newTweetLinks.children('#msgUpdtBtn').attr('onclick', 'updateForm(' + messageId + ')');

    /*change delete message btn form action route to actual id*/
    newTweetLinks.children('#msgDltBtn').attr('onclick', 'deleteTweet(' + messageId + ')');

    /*change comment btn route to actual id*/
    newTweetLinks.children('#cmntBtn').attr('onclick', 'commentForm(' + messageId + ')');
}


Echo.private('messageUpdate')
    .listen('.messageUpdated', (data) => {
        wsUpdateTweet(data);
    });