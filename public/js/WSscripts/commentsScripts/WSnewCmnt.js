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


function createComment(data) {

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
        : '<button id="banCommBtn">' +
        '<a href="/api/v1/ban/message/' + data.comment['message_id'] + '/comment/'+ data.comment['id'] +'">' +
        '<i class="fa fa-ban" aria-hidden="true"></i></a>' +
        '</button>';

    //doimplementovat odpocet dvou minut
    var updtBtn = (authUserId == data.user['user_id'])
        ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + data.comment['id'] + ')">' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>'
        : '';

    //doimplementovat odpocet dvou minut
    var dltBtn = (authUserId == data.user['user_id'])
        ? '<form method="POST" action="/api/v1/tweet/'+ data.comment['message_id'] +'/comment/'+ data.comment['id'] +'">' +
        '<input type="hidden"  name="_token" value="'+ csrfToken +'">'+
        '<input type="hidden" name="_method" value="DELETE">'+
        '<button id="msgDltBtn" type="submit">' +
        '<i class="fa fa-times" aria-hidden="true"></i>' +
        '</button>' +
        '</form>'
        : '';


    var html =
        '<div class="comment" data-id="' + data.comment['id'] + '"> ' +
            '<div class="comment-name">' + userName + '' + banBtn +
                '<span class="up-del-links">' + msgBan + updtBtn + dltBtn + '</span>' +
                '<span class="comment-time">' + formatDate(data.comment['created_at']['date']) + '</span>' +
            '</div>' +
            '<div class="comment-text" data-comment-id="' + data.comment['id'] + '" data-tweet-id="'+ data.comment['message_id'] +'">' +
            data.comment['text'] + '</div>' +
        '</div>';

    $('.tweet[data-id='+ data.comment['message_id'] +']').children('.comments-container').prepend(html);
}


/****************************************************************************************/

var pusher = new Pusher('4ddf59eb5af2754e89f0', {
    cluster: 'eu',
    encrypted: true
});

var channel = pusher.subscribe('comment');
channel.bind('newComment', function (data) {

    if (csrfToken != data.csrfTok) {
        createComment(data);
    }

});