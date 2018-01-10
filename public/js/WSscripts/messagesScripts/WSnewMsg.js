/**
 * Date format for DisplayTasks method
 * @param timeString
 * @returns {string}
 */
function formatDate(timeString) {

    var date = new Date(timeString);
    var day = date.getDate();
    var month = date.getUTCMonth() + 1;
    var year = date.getUTCFullYear();

    var hour = date.getHours();
    var minutes = (date.getMinutes() < 10 ) ? '0' + date.getMinutes() : date.getMinutes();
    var seconds = (date.getSeconds() < 10) ? '0' + date.getSeconds() : date.getSeconds();

    var formatedDate = hour + ':' + minutes + ':' + seconds + ' / ' + day + '.' + month + '.' + year;

    return formatedDate;
}

/****************************************************************************************/


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
            '</div>'+
        '</div>';

    $('.panel-body').prepend(html);
}


/****************************************************************************************/

var pusher = new Pusher('4ddf59eb5af2754e89f0', {
    cluster: 'eu',
    encrypted: true
});


var channel = pusher.subscribe('message');
channel.bind('newMessage', function (data) {
    if(authUserId != data.user['user_id']) {
        createTweet(data);
    }
});
