function commentForm(id) {

    if (!$('#tweetForm').length) {
        var form = $('<form></form>');
        form.attr('method', 'POST');
        form.attr('id', 'tweetForm');
        form.attr('action', 'tweet/' + id + '/comment');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var csrfInput = $('<input>');
        csrfInput.attr('type', 'hidden');
        csrfInput.attr('name', '_token');
        csrfInput.attr('value', csrfToken);

        var textarea = $('<textarea></textarea>');
        textarea.attr('id', 'tweetTextarea');
        textarea.attr('name', 'comment');

        form.prepend(csrfInput);
        form.prepend(textarea);

        var buttonsDiv = $('<div></div>');
        buttonsDiv.attr('id', 'buttons-div');

        var submitBtn = $('<button></button>');
        submitBtn.attr('type', 'submit');
        submitBtn.attr('class', 'btn btn-success');
        submitBtn.attr('id', 'submitBtn');
        submitBtn.text('Send');

        var cancelBtn = $('<button></button>');
        cancelBtn.attr('id', 'cancelBtn');
        cancelBtn.attr('class', 'btn btn-warning');
        cancelBtn.text('Cancel');

        buttonsDiv.append(submitBtn);
        buttonsDiv.append(cancelBtn);

        form.append(buttonsDiv);

        $('#tweet-form').prepend(form);

        cancelBtn.click(function () {
            $('#tweetForm').remove();
        });
    }
    else {
        $('#tweetForm').remove();
    }
}