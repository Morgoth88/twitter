
var csrfToken = $('meta[name=csrf-token]').attr('content');

function updateTweet(data) {

    var oldTweet = $('.tweet[data-id='+ data.message['old_id'] +']');

    /*change tweet id and text*/
    var tweetText = oldTweet.children('.tweet-text');
    tweetText.attr('data-id',data.message['id']);
    tweetText.text(data.message['text']);

    oldTweet.attr('data-id', data.message['id']);

    var newTweet = $('.tweet[data-id='+ data.message['id'] +']');

    /*change message btn route to actual id*/
    newTweet.children('.tweet-name').children('.up-del-links')
        .children('#banMessBtn').children('a')
        .attr('href','/api/v1/ban/message/' + data.message['id']);

    /*change update message btn to actual id*/
    newTweet.children('.tweet-name').children('.up-del-links')
        .children('#msgUpdtBtn').attr('onclick', 'updateForm('+ data.message['id'] +')');

    /*change delete message btn form action route to actual id*/
    newTweet.children('.tweet-name').children('.up-del-links')
        .children('form').attr('action','/api/v1/tweet/' + data.message['id']);

    /*change comment btn route to actual id*/
    newTweet.children('.tweet-icons').children('.comment-link')
        .children('#cmntBtn').attr('onclick', 'commentForm('+ data.message['id'] +')');
}

/****************************************************************************************/


Echo.private('messageUpdate')
    .listen('.msgUpdt', (data) => {
        updateTweet(data);
    });