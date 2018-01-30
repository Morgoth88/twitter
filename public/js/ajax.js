$(document).ready(function () {
    getTweets();
});

function allComments(id) {

    $.ajax({
        url: '/api/v1/tweet/'+ id +'/comment',
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        $('.tweet[data-id='+ id +']').children('.comments-container').children('.comment').each(function () {
            $(this).remove();
        })

        newPage = parseInt($('.panel-body').attr('data-page'));

        $('.tweet[data-id='+ id +']').children('.comments-container').children('.allLink').text('hide');
        $('.tweet[data-id='+ id +']').children('.comments-container').children('.allLink').attr('onclick','getTweets('+ newPage +')');

        for(x in data) {

            var msgId = data[x].message_id;
            var CmntId = data[x].id;
            var d = new Date();

            var c = new Date(data[x].created_at);
            var passed = (d.getTime()-c.getTime())/1000/60;


            var CmntUserName = (authUserRole == 0 )
                ? data[x].user['name']
                : '<a href="/api/v1/user/' + data[x].user['id'] + '">' + data[x].user['name'] + '</a>';


            if((authUserRole == 0)) {
                var CommentUserBanBtn = '';
            }
            else
            {
                if (data[x].user['role_id'] == 1) {
                    var CommentUserBanBtn = '';
                }
                else
                {
                    var CommentUserBanBtn =  '<button id="banUserBtn" onclick="banUser('+ data[x].user['id'] +')">' +
                        '<i class="fa fa-ban" aria-hidden="true"></i>' +
                        '</button>';
                }
            }

            if((authUserRole == 0)) {
                var CommentBan = '';
            }
            else
            {
                if (data[x].user['role_id'] == 1) {
                    var CommentBan = '';
                }
                else
                {
                    var CommentBan =  '<button id="banCommBtn" onclick="banCmnt('+ CmntId+')">' +
                        '<i class="fa fa-ban" aria-hidden="true"></i>' +
                        '</button>';
                }
            }

            var CommentUpdtBtn = (authUserId == data[x].user['id'] && passed <= 2)
                ? '<button id="msgUpdtBtn" onclick="commentUpdateForm(' + data[x].id + ')">' +
                '<i class="fa fa-pencil" aria-hidden="true"></i>' +
                '</button>'
                : '';


            var CommentDltBtn = (authUserId == data[x].user['id'] && passed <= 2)
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
                '<span class="comment-time" data-time ="'+  data[x].created_at +'"></span>' +
                '</div>' +
                '<div class="comment-text" data-comment-id="' + data[x].id + '" data-tweet-id="' + msgId + '">' +
                data[x].text + '</div>' +
                '</div>';


            $('.tweet[data-id=' + msgId + ']').children('.comments-container').prepend(CmntHtml);

            var commentCount =  $('.tweet[data-id=' + msgId + ']').children('.comments-container').children('.comment').length;
            var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

            $('.tweet[data-id=' + msgId + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);
        }

        /*tweets & comment created at time update every minute*/
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
                var timestamp = new Date(time);

                $(this).text(moment(time).fromNow());
            });
            $('.comment-time').each(function () {
                var time = $(this).attr('data-time');
                var timestamp = new Date(time);

                $(this).text(moment(time).fromNow());
            });
        },60000);
        /*******************************************************************************************************************/


        /*updt & dlt btns will be removed after two minutes*/
        /*******************************************************************************************************************/
        setTimeout(function () {
            $('.up-del-links').each(function () {
                $(this).children('#msgDltBtn').hide();
                $(this).children('#msgUpdtBtn').hide();
            })
        },120000)

    });
}
function getTweets(page = 1) {

    $.ajax({
        url: '/api/v1/tweet?page='+ page,
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        var csrfToken = $('meta[name=csrf-token]').attr('content');

        $('.tweet').each(function () {
            $(this).remove();
        })

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
                'new <i class="fa fa-comments" aria-hidden="true"></i>' +
                '</button>' +
                '</span>'+
                '<span class="comment-count"></span>'+
                '</div>'+
                '</div>';

            $('.panel-body').append(html)


            /**********************************************************************************************************/


            for(x in data.data[i].comment) {
                if(x >= 3){

                    var html = '<span class="allLink" onclick="allComments('+ data.data[i].id +')">all comments</span>' ;

                    var link = $('.tweet[data-id='+ data.data[i].id +']').children('.comments-container').children('.allLink').length

                    if(!link) {
                        $('.tweet[data-id='+ data.data[i].id +']').children('.comments-container').append(html);
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

                var commentCount =  data.data[i].comment.length;
                var commentCounter = (commentCount == 1) ? commentCount + ' comment' : commentCount + ' comments';

                $('.tweet[data-id=' + msgId + ']').children('.tweet-icons').children('.comment-count').text(commentCounter);
            }
            /************************************************************************************************************/
        }


        $('.panel-body').attr('data-page',data.current_page);

        /*pagination*/
        /*******************************************************************************************************************/
        if (data.last_page <= data.current_page) {
            $('#next').remove();
        }
        else {
            var newPage = data.current_page + 1;

            if ($('#next').length == 0) {
                var next = $('<button id="next" onclick="getTweets(' + newPage + ')">next</button>');
                $('.pagination_buttons').append(next);
            }
            else {
                $('#next').attr('onclick', 'getTweets(' + newPage + ')')
            }
        }

        if (data.current_page > 1) {

            var newPage = data.current_page - 1;

            if ($('#previous').length == 0) {
                var previous = $('<button id="previous" onclick="getTweets(' + newPage + ')">previous</button>');
                $('.pagination_buttons').prepend(previous);
            }
            else {
                $('#previous').attr('onclick', 'getTweets(' + newPage + ')')
            }
        }
        else {
            $('#previous').remove();
        }
        /*******************************************************************************************************************/


        /*tweets & comment created at time update every minute*/
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
                var timestamp = new Date(time);

                $(this).text(moment(time).fromNow());
            });
            $('.comment-time').each(function () {
                var time = $(this).attr('data-time');
                var timestamp = new Date(time);

                $(this).text(moment(time).fromNow());
            });
        },60000);
        /*******************************************************************************************************************/


        /*updt & dlt btns will be removed after two minutes*/
        /*******************************************************************************************************************/
        setTimeout(function () {
            $('.up-del-links').each(function () {
                $(this).children('#msgDltBtn').hide();
                $(this).children('#msgUpdtBtn').hide();
            })
        },120000)
        /*******************************************************************************************************************/

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

