$(document).ready(function () {
    getTweets();
});

function getTweets() {

    $.ajax({
        url: '/api/v1/tweet',
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        var csrfToken = $('meta[name=csrf-token]').attr('content');

        for(i in data.data) {

            var c = new Date(data.data[i].created_at);
            var d = new Date();
            var passed = (d.getTime()-c.getTime())/1000/60;



            var userName = (authUserRole == 0 )
                ? data.data[i].user['name']
                : '<a href="/api/v1/user/' + data.data[i].user['id'] + '">' + data.data[i].user['name'] + '</a>';


            if((authUserRole == 0)) {
                var banBtn = '';
            }
            else
            {
                if (data.data[i].user['role_id'] == 1) {
                    var banBtn = '';
                }
                else
                {
                    var banBtn =  '<button id="banUserBtn" onclick="banUser('+  data.data[i].user['id'] +')">' +
                    '<i class="fa fa-ban" aria-hidden="true"></i>' +
                    '</button>';
                }
            }


            if((authUserRole == 0)) {
                var msgBan = '';
            }
            else
            {
                if (data.data[i].user['role_id'] == 1) {
                    var msgBan = '';
                }
                else
                {
                    var msgBan =  '<button id="banMessBtn" onclick="banTweet('+ data.data[i].id  +')">' +
                        '<i class="fa fa-ban" aria-hidden="true"></i>' +
                        '</button>';
                }
            }

            var updtBtn = (authUserId == data.data[i].user['id'] && passed <= 2)
                ? '<button id="msgUpdtBtn" onclick="updateForm(' + data.data[i].id + ')">' +
                '<i class="fa fa-pencil" aria-hidden="true"></i>' +
                '</button>'
                : '';


            var dltBtn = (authUserId == data.data[i].user['id'] && passed <= 2)
                ? '<button id="msgDltBtn" onclick="deleteTweet(' + data.data[i].id + ')">' +
                '<i class="fa fa-times" aria-hidden="true"></i>' +
                '</button>'
                : '';


            var html =
                '<div class="tweet" data-id="' + data.data[i].id + '"> ' +
                '<div class="tweet-name">' + userName + '' + banBtn +
                '<span class="up-del-links">' + msgBan + updtBtn + dltBtn + '</span>' +
                '</div>' +
                '<div class="tweet-time-div">' +
                '<span class="tweet-time" data-time="'+  data.data[i].created_at +'"></span>' +
                '</div>' +
                '<div class="tweet-text" data-id="' +  data.data[i].id + '">' +  data.data[i].text + '</div>' +
                '<div class="tweet-icons">'+
                '<span class="comment-link">' +
                '<button id="cmntBtn" onclick="commentForm('+ data.data[i].id +')">' +
                '<i class="fa fa-comments" aria-hidden="true"></i>' +
                '</button>' +
                '</span>'+
                '<span class="comment-count"></span>'+
                '</div>'+
                '</div>';

            $('.panel-body').prepend(html)


            /**********************************************************************************************************/


            for(x in data.data[i].comment) {
                if(x >= 3){

                    var html = '<a href="/api/v1/tweet/'+ data.data[i].id +'/comment/">all comments</a>' ;

                    var link = $('.comments-container').children('a').length

                    if(!link) {
                        $('.comments-container').append(html);
                    }
                    break;
                }

                var msgId = data.data[i].id;
                var CmntId = data.data[i].comment[x].id;

                var c = new Date(data.data[i].comment[x].created_at);
                var passed = (d.getTime()-c.getTime())/1000/60;


                var CmntUserName = (authUserRole == 0 )
                    ? data.data[i].comment[x].user['name']
                    : '<a href="/api/v1/user/' + data.data[i].comment[x].user['id'] + '">' + data.data[i].comment[x].user['name'] + '</a>';


                if((authUserRole == 0)) {
                    var CommentUserBanBtn = '';
                }
                else
                {
                    if (data.data[i].comment[x].user['role_id'] == 1) {
                        var CommentUserBanBtn = '';
                    }
                    else
                    {
                        var CommentUserBanBtn =  '<button id="banUserBtn" onclick="banUser('+ data.data[i].comment[x].user['id'] +')">' +
                            '<i class="fa fa-ban" aria-hidden="true"></i>' +
                            '</button>';
                    }
                }

                if((authUserRole == 0)) {
                    var CommentBan = '';
                }
                else
                {
                    if (data.data[i].comment[x].user['role_id'] == 1) {
                        var CommentBan = '';
                    }
                    else
                    {
                        var CommentBan =  '<button id="banCommBtn" onclick="banCmnt('+ CmntId+')">' +
                            '<i class="fa fa-ban" aria-hidden="true"></i>' +
                            '</button>';
                    }
                }

                var CommentUpdtBtn = (authUserId == data.data[i].comment[x].user['id'] && passed <= 2)
                    ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + data.data[i].comment[x].id + ')">' +
                    '<i class="fa fa-pencil" aria-hidden="true"></i>' +
                    '</button>'
                    : '';


                var CommentDltBtn = (authUserId == data.data[i].comment[x].user['id'] && passed <= 2)
                    ? '<button id="msgDltBtn" onclick="deleteCmnt('+ CmntId +')">' +
                    '<i class="fa fa-times" aria-hidden="true"></i>' +
                    '</button>'
                    : '';

                if ($('.tweet[data-id=' + msgId + ']').children('.comments-container').length < 1) {

                    $('.tweet[data-id=' + msgId + ']').append('<div class="comments-container"></div>')
                }

                var CmntHtml =
                    '<div class="comment" data-id="' + CmntId + '"> ' +
                    '<div class="comment-name">' + CmntUserName + '' + CommentUserBanBtn +
                    '<span class="up-del-links">' + CommentBan+ CommentUpdtBtn + CommentDltBtn + '</span>' +
                    '<span class="comment-time" data-time ="'+  data.data[i].comment[x].created_at +'"></span>' +
                    '</div>' +
                    '<div class="comment-text" data-comment-id="' + data.data[i].comment[x].id + '" data-tweet-id="' + msgId + '">' +
                    data.data[i].comment[x].text + '</div>' +
                    '</div>';

                $('.tweet[data-id=' + msgId + ']').children('.comments-container').append(CmntHtml);

                var commentCount = data.data[i].comment.length;
                var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

                $('.tweet[data-id=' + msgId + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);
            }
            /************************************************************************************************************/
        }

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
                var timestamp = new Date(time);

                $(this).text(moment(time).fromNow());
            });
            $('.comment-time').each(function () {
                var time = $(this).attr('data-time');
                var timestamp = new Date(time);

                $(this).text(moment(time).fromNow());
            });
        },60000);

        setTimeout(function () {
            $('.up-del-links').each(function () {
                $(this).children('#msgDltBtn').hide();
                $(this).children('#msgUpdtBtn').hide();
            })
        },120000)

    });

}


function deleteTweet(id) {
    $.ajax({
        url: '/api/v1/tweet/' + id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function (data) {

    });
}


function banTweet(id) {
    $.ajax({
        url: '/api/v1/ban/message/' + id,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function (data) {

    });
}

function banUser(id) {
    $.ajax({
        url: '/api/v1/ban/user/' + id,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function (data) {

    });
}

function deleteCmnt(CmntId) {

    var msgId = $('.comment[data-id='+ CmntId +']').parent('.comments-container').parent('.tweet').attr('data-id');

    $.ajax({
        url: '/api/v1/tweet/' + msgId + '/comment/' + CmntId,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function (data) {

    });
}


function banCmnt(CmntId) {

    var msgId = $('.comment[data-id='+ CmntId +']').parent('.comments-container').parent('.tweet').attr('data-id');

    $.ajax({
        url: '/api/v1/ban/message/' + msgId + '/comment/' + CmntId,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function (data) {

    });
}

