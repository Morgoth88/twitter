var csrfToken = $('meta[name=csrf-token]').attr('content');

function createTweet(data) {

    var userName = (authUserRole == 0 )
        ? data.user['userName']
        : '<a href="/api/v1/user/' + data.user['user_id'] + '">' + data.user['userName'] + '</a>';

    var banBtn = (authUserRole == 0 )
        ? ''
        : '<button id="banUserBtn">' +
        '<a href="/api/v1/ban/user/' + data.user['user_id'] + '">' +
        '<i class="fa fa-ban" aria-hidden="true"></i></a>' +
        '</button>';

    var msgBan = (authUserRole == 0 )
        ? ''
        : '<button id="banMessBtn">' +
        '<a href="/api/v1/ban/message/' + data.message['id'] + '">' +
        '<i class="fa fa-ban" aria-hidden="true"></i></a>' +
        '</button>';

    //doimplementovat odpocet dvou minut
    var updtBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgUpdtBtn" onclick="updateForm(' + data.message['id'] + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';

    //doimplementovat odpocet dvou minut
    var dltBtn = (authUserId == data.user['user_id'])
        ? '<form method="POST" action="/api/v1/tweet/'+ data.message['id'] +'">' +
        '<input type="hidden"  name="_token" value="'+ csrfToken +'">'+
        '<input type="hidden" name="_method" value="DELETE">'+
        '<button id="msgDltBtn" type="submit">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>' +
        '</form>'
        : '';


    var html =
        '<div class="tweet" data-id="' + data.message['id'] + '"> ' +
            '<div class="tweet-name">' + userName + '' + banBtn +
                '<span class="up-del-links">' + msgBan + updtBtn + dltBtn + '</span>' +
            '</div>' +
            '<div class="tweet-time-div">' +
                '<span class="tweet-time">' + moment().startOf(data.message['created_at']).fromNow() + '</span>' +
            '</div>' +
            '<div class="tweet-text" data-id="' + data.message['id'] + '">' + data.message['text'] + '</div>' +
            '<div class="tweet-icons">'+
                '<span class="comment-link">' +
                    '<button id="cmntBtn" onclick="commentForm('+ data.message['id'] +')">' +
                        '<i class="fa fa-comments" aria-hidden="true"></i>' +
                    '</button>' +
                '</span>'+
                '<span class="comment-count"></span>'+
            '</div>'+
        '</div>';

    $('.panel-body').prepend(html);
}


/****************************************************************************************/

Echo.private('message')
    .listen('.newMessage', (data) => {
        if(authUserId != data.user['user_id']) {
            createTweet(data);
        }
    });

