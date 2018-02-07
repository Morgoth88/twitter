function wsCreateTweet(data) {

    let userId = data.user['user_id'];
    let userName = data.user['userName'];
    let userRole = data.user['userRole'];

    let messageId = data.message['id'];
    let messageCreatedAt = data.message['created_at'].date;
    let messageText = data.message['text'];

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

    $('.panel-body').prepend(generateMessageHtml(messageHtmlData));


    //pagination
    /**************************************************************************/
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
    /**************************************************************************/
    let messageTimeSpan = $('.tweet[data-id=' + messageId + ']').children('.tweet-time-div').children('.tweet-time');
    let messageTime = messageTimeSpan.attr('data-time');
    messageTimeSpan.text(moment(messageTime).fromNow());

    /*update & delete buttons will be removed after two minutes*/
    /**************************************************************************/
    hideButtons();
}


Echo.private('message')
    .listen('.messageCreated', (data) => {
        wsCreateTweet(data);
    });


/******************************************************************************/
/******************************************************************************/

function wsDeleteMessage(data) {

    let tweet = $('.tweet[data-id=' + data.message['id'] + ']');
    tweet.remove();

    if ($('.tweet').length < 6) {
        $('#next').remove();
    }
}

Echo.private('messageDelete')
    .listen('.messageDeleted', (data) => {
        wsDeleteMessage(data);
    });


Echo.private('messageBanned')
    .listen('.messageBanned', (data) => {
        wsDeleteMessage(data);
    });


/******************************************************************************/
/******************************************************************************/


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