function createForm() {

    if (!$('#tweetForm').length) {
        var label = $('<div></div>');
        label.attr('id', 'formLabel');
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

        var submitBtn = $('<button><i class="fa fa-check" aria-hidden="true" fa-3x></i></button>');
        submitBtn.attr('type', 'submit');
        submitBtn.attr('class', 'btn btn-success');
        submitBtn.attr('id', 'submitBtn');

        var cancelBtn = $('<button><i class="fa fa-undo" aria-hidden="true" fa-3x></i></button>');
        cancelBtn.attr('id', 'cancelBtn');
        cancelBtn.attr('class', 'btn btn-warning');

        buttonsDiv.append(submitBtn);
        buttonsDiv.append(cancelBtn);

        form.append(buttonsDiv);

        var tweet_form = $('<div id="tweet-form"></div>');

        tweet_form.prepend(form);

        $('.panel-heading').append(tweet_form);

        submitBtn.click(function (event) {
            event.preventDefault();

            var newTweet = textarea.val()

            var data = {
                tweet: newTweet
            }

            $.ajax({
                url: '/api/v1/tweet',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data),
                statusCode: {
                    422: function() {
                        $('#tweetTextarea').attr('placeholder','please fill' +
                            ' this field correctly');
                        $('#tweetTextarea').css({'border-color':'red',
                            'box-shadow':'0 0 17px red'});
                    }}
            }).done(function (data) {
                tweet_form.remove();
            });

        });

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
        label.attr('id', 'formLabel');
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

        var submitBtn = $('<button><i class="fa fa-check" aria-hidden="true" fa-3x></i></button>');
        submitBtn.attr('type', 'submit');
        submitBtn.attr('class', 'btn btn-success');
        submitBtn.attr('id', 'submitBtn');

        var cancelBtn = $('<button><i class="fa fa-undo" aria-hidden="true" fa-3x></i></button>');
        cancelBtn.attr('id', 'cancelBtn');
        cancelBtn.attr('class', 'btn btn-warning');

        buttonsDiv.append(submitBtn);
        buttonsDiv.append(cancelBtn);

        form.append(buttonsDiv);

        var tweet_up_form_div = $('<div></div>');
        tweet_up_form_div.attr('id', 'tweet-update-form');


        tweet_up_form_div.prepend(form);

        $('div[data-id=' + id + ']').closest('.tweet-text').prepend(tweet_up_form_div);

        submitBtn.click(function (event) {
            event.preventDefault();

            var newTweet = textarea.val()
            var data = {
                tweet: newTweet,
                id: id
            }

            $.ajax({
                url: '/api/v1/tweet/' + id,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data)
            }).done(function (data) {

                tweet_up_form_div.remove();
            });

        });


        cancelBtn.click(function () {
            $('#tweetForm').remove();
        });
    }
    else {
        $('#tweetForm').remove();
    }
}