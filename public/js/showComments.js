function showComment(messageId) {
    $.ajax({
        method: 'GET',
        url: 'tweet/' + messageId + '/comment',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function (data) {

        }
    );
}