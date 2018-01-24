var csrfToken = $('meta[name=csrf-token]').attr('content');

function createTweet(data) {

    var userName = (authUserRole == 0 )
        ? data.user['userName']
        : '<a href="/api/v1/user/' + data.user['user_id'] + '">' + data.user['userName'] + '</a>';


    if((authUserRole == 0)) {
        var banBtn = '';
    }
    else
    {
        if (data.user['userRole'] == 1) {
            var banBtn = '';
        }
        else
        {
            var banBtn =  '<button id="banUserBtn" onclick="banUser('+  data.user['user_id'] +')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }


    if((authUserRole == 0)) {
        var msgBan = '';
    }
    else
    {
        if (data.user['userRole']  == 1) {
            var msgBan = '';
        }
        else
        {
            var msgBan =  '<button id="banMessBtn" onclick="banTweet('+ data.message['id']  +')">' +
                '<i class="fa fa-ban" aria-hidden="true"></i>' +
                '</button>';
        }
    }


    var updtBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgUpdtBtn" onclick="updateForm(' + data.message['id'] + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';


    var dltBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgDltBtn" onclick="deleteTweet('+ data.message['id'] +')">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>'
        : '';


    var html =
        '<div class="tweet" data-id="' + data.message['id'] + '"> ' +
        '<div class="tweet-name">' + userName + '' + banBtn +
        '<span class="up-del-links">' + msgBan + updtBtn + dltBtn + '</span>' +
        '</div>' +
        '<div class="tweet-time-div">' +
        '<span class="tweet-time" data-time="'+ data.message['created_at'].date +'"></span>' +
        '</div>' +
        '<div class="tweet-text" data-id="' + data.message['id'] + '">' + data.message['text'] + '</div>' +
        '<div class="tweet-icons">'+
        '<span class="comment-link">' +
        '<button id="cmntBtn" onclick="commentForm('+ data.message['id'] +')">' +
        'new <i class="fa fa-comments" aria-hidden="true"></i>' +
        '</button>' +
        '</span>'+
        '<span class="comment-count"></span>'+
        '</div>'+
        '</div>';


    $('.panel-body').prepend(html);


    /*pagination*/
    /*******************************************************************************************************************/
    if ($('.tweet').length < 6) {
        $('#next').remove();
    }
    else {

        $('.tweet').eq(5).remove();

        newPage = parseInt($('.panel-body').attr('data-page')) + 1;

        if ($('#next').length == 0) {
            var next = $('<button id="next" onclick="getTweets(' + newPage + ')">next</button>');
            $('.pagination_buttons').append(next);
        }
    }

    /*******************************************************************************************************************/


    $('.tweet-time').each(function () {
        var time = $(this).attr('data-time');
        $(this).text(moment(time).fromNow());
    });
    $('.comment-time').each(function () {
        var time = $(this).attr('data-time');
        $(this).text(moment(time).fromNow());
    });


    setInterval(function () {
        $('.tweet-time').each(function () {
            var time = $(this).attr('data-time');
            $(this).text(moment(time).fromNow());
        });
        $('.comment-time').each(function () {
            var time = $(this).attr('data-time');
            $(this).text(moment(time).fromNow());
        });
    },60000);


    setTimeout(function () {
        $('.tweet[data-id='+  data.message['id'] +']').children('.tweet-name').children('.up-del-links').children('#msgDltBtn').hide();
        $('.tweet[data-id='+  data.message['id'] +']').children('.tweet-name').children('.up-del-links').children('#msgUpdtBtn').hide();
    },120000)
}


/****************************************************************************************/

Echo.private('message')
    .listen('.newMessage', (data) => {
        createTweet(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/

function dltMessage(data) {

    var tweet = $('.tweet[data-id=' + data.message['id'] + ']');
    tweet.remove();
}

Echo.private('messageDelete')
    .listen('.msgDel', (data) => {
        dltMessage(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/


function banMessage(data) {

    var tweet = $('.tweet[data-id='+ data.message['id'] +']');
    tweet.remove();
}


Echo.private('messageBanned')
    .listen('.msgBan', (data) => {
        banMessage(data);
    });


/****************************************************************************************/
/****************************************************************************************/
/****************************************************************************************/



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
        .children('#banMessBtn').attr('onclick','banTweet('+ data.message['id'] +')');

    /*change update message btn to actual id*/
    newTweet.children('.tweet-name').children('.up-del-links')
        .children('#msgUpdtBtn').attr('onclick', 'updateForm('+ data.message['id'] +')');

    /*change delete message btn form action route to actual id*/
    newTweet.children('.tweet-name').children('.up-del-links')
        .children('#msgDltBtn').attr('onclick','deleteTweet(' + data.message['id'] +')');

    /*change comment btn route to actual id*/
    newTweet.children('.tweet-icons').children('.comment-link')
        .children('#cmntBtn').attr('onclick', 'commentForm('+ data.message['id'] +')');
}

/****************************************************************************************/


Echo.private('messageUpdate')
    .listen('.msgUpdt', (data) => {
        updateTweet(data);
    });