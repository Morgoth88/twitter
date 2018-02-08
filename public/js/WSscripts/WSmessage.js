function wsCreateTweet(data) {

    //message data
    /**************************************************************************/
    let userId = data.user['user_id'];
    let userName = data.user['userName'];
    let userRole = data.user['userRole'];

    let messageId = data.message['id'];
    let messageCreatedAt = data.message['created_at'].date;
    let messageText = data.message['text'];

    //generate comment html
    /**************************************************************************/
    let messageHtmlData = {
        messageId : messageId,
        messageText : messageText,
        messageCreatedAt : messageCreatedAt,
        messageUserName :  generatePostUserName(userName, userId),
        userBanButton : generateUserBanButton(userRole, userId),
        messageBanButton : generateMessageBanButton(userRole, messageId),
        messageUpdateButton : generateMessageUpdateButton(userId, messageId, messageCreatedAt),
        messageDeleteButton : generateMessageDeleteButton(userId, messageId, messageCreatedAt)
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
/*******************************************************************************/

function wsUpdateTweet(data) {

    let oldMessage = $('.tweet[data-id=' + data.message['old_id'] + ']');

    let newMessageId = data.message['id'];
    let newMessageText = data.message['text'];

    /*change tweet id and text*/
    let tweetText = oldMessage.children('.tweet-text');
    tweetText.attr('data-id', newMessageId);
    tweetText.html(newMessageText);

    oldMessage.attr('data-id', newMessageId);

    let newTweet = $('.tweet[data-id=' + newMessageId + ']');
    let newTweetLinks = newTweet.children('.tweet-name').children('.up-del-links');



    /*change message btn route to actual id*/
    newTweetLinks.children('#banMessBtn').attr('onclick', 'banTweet(' + newMessageId + ')');

    /*change update message btn to actual id*/
    newTweetLinks.children('#msgUpdtBtn').attr('onclick', 'updateForm(' + newMessageId + ')');

    /*change delete message btn form action route to actual id*/
    newTweetLinks.children('#msgDltBtn').attr('onclick', 'deleteTweet(' + newMessageId + ')');

    /*change comment btn route to actual id*/
    newTweet.children('.tweet-icons').children('.comment-link').children('#cmntBtn').attr('onclick', 'commentForm(' + newMessageId + ')');
}


Echo.private('messageUpdate')
    .listen('.messageUpdated', (data) => {
        wsUpdateTweet(data);
    });