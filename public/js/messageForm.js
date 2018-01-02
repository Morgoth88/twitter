function createForm() {

    if (!$('#tweetForm').length) {
        var label = $('<div></div>');
        label.attr('id','formLabel');
        label.html('<strong>New tweet</strong>');

        var form = $('<form></form>');
        form.attr('method', 'POST');
        form.attr('id', 'tweetForm');
        form.attr('action', '/api/v1/tweet');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var csrfInput = $('<input>');
        csrfInput.attr('type', 'hidden');
        csrfInput.attr('name', '_token');
        csrfInput.attr('value', csrfToken);

        var textarea = $('<textarea></textarea>');
        textarea.attr('id', 'tweetTextarea');
        textarea.attr('name', 'tweet');

        form.prepend(csrfInput);
        form.prepend(textarea);
        form.prepend(label);

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

function updateForm(id) {

    var tweet = $('.tweet-text[data-id=' + id + ']').text();

    if (!$('#tweetForm').length) {

        var label = $('<div></div>');
        label.attr('id','formLabel');
        label.html('<strong>Update tweet</strong>');

        var form = $('<form></form>');
        form.attr('method', 'POST');
        form.attr('id', 'tweetForm');
        form.attr('action', '/api/v1/tweet/' + id);

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var csrfInput = $('<input>');
        csrfInput.attr('type', 'hidden');
        csrfInput.attr('name', '_token');
        csrfInput.attr('value', csrfToken);

        var methodInput = $('<input>');
        methodInput.attr('type', 'hidden');
        methodInput.attr('name', '_method');
        methodInput.attr('value', 'PUT');

        var textarea = $('<textarea></textarea>');
        textarea.attr('id', 'tweetTextarea');
        textarea.attr('name', 'tweet');

        textarea.val(tweet);

        form.prepend(csrfInput);
        form.prepend(methodInput);
        form.prepend(textarea);
        form.prepend(label);

        var buttonsDiv = $('<div></div>');
        buttonsDiv.attr('id', 'buttons-div');

        var submitBtn = $('<button></button>');
        submitBtn.attr('type', 'submit');
        submitBtn.attr('class', 'btn btn-success');
        submitBtn.attr('id', 'submitBtn');
        submitBtn.text('update');

        var cancelBtn = $('<button></button>');
        cancelBtn.attr('id', 'cancelBtn');
        cancelBtn.attr('class', 'btn btn-warning');
        cancelBtn.text('Cancel');

        buttonsDiv.append(submitBtn);
        buttonsDiv.append(cancelBtn);

        form.append(buttonsDiv);

        var tweet_up_form_div = $('<div></div>');
        tweet_up_form_div.attr('id','tweet-update-form');


        tweet_up_form_div.prepend(form);

        $('div[data-id=' + id + ']').closest('.tweet-text').prepend(tweet_up_form_div);




        cancelBtn.click(function () {
            $('#tweetForm').remove();
        });
    }
    else {
        $('#tweetForm').remove();
    }
}