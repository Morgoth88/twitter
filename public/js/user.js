$(document).ready(function () {

    let res = $(location).attr('href').split('/');
    let id = res[res.length - 1];

    $.ajax({
        url: '/api/v1/info/user/' + id,
        method: 'GET',
        dataType: 'json'
    }).done(function (data) {

        let html = '<tr><td>User ID: </td><td>'+ data.user['id'] +'</td></tr>' +
            '<tr><td>User role: </td><td>'+  data.user['role_id'] +'</td></tr>' +
            '<tr><td>User name: </td><td>'+  data.user['name'] +'</td></tr>' +
            '<tr><td>User email: </td><td>'+  data.user['email'] +'</td></tr>' +
            '<tr><td>Registered:</td><td>'+  data.user['created_at'] +'</td></tr>' +
            '<tr><td>Message count: </td><td>'+ data.messagesCount +'</td></tr>' +
            '<tr><td>Comment count: </td><td>'+ data.commentsCount  +'</td></tr>' +
            '<tr><td>Last message created at: </td><td>'+ data.lastCreatedMessageDate +'</td></tr>' +
            '<tr><td>Last comment created at: </td><td>'+ data.lastCreatedCommentDate +'</td></tr>';

        $('.table').prepend(html);
    });

});