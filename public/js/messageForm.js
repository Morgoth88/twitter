function createForm() {

    let tweetForm = $('#tweetForm');

    if (!tweetForm.length) {

        let form = $('<form id="tweetForm" ></form>');

        let label = $('<div id="formLabel"><strong>New tweet</strong></div>');

        let textArea = $('<textarea id="tweetTextarea" name="tweet"></textarea>');

        form.prepend(textArea);
        form.prepend(label);



        let buttonsDiv = $('<div id="buttons-div"></div>');

        let submitButton = $('<button type="submit" class="btn btn-success"' +
            ' id="submitBtn"><i class="fa fa-check" aria-hidden="true" fa-3x></i>' +
            '</button>');

        let cancelButton = $('<button id="cancelBtn" class="btn btn-warning">' +
            '<i class="fa fa-undo" aria-hidden="true" fa-3x></i>' +
            '</button>');

        buttonsDiv.append(submitButton);
        buttonsDiv.append(cancelButton);
        form.append(buttonsDiv);


        let tweetForm = $('<div id="tweet-form"></div>');
        tweetForm.html(form);


        $('.panel-heading').append(tweetForm);


        /**********************************************************************/


        submitButton.click(function (event) {
            event.preventDefault();

            let newTweet = textArea.val();

            let data = {
                tweet: newTweet
            };

            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/api/v1/tweet',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data),
                statusCode: {
                    422: function () {
                        textArea.attr('placeholder', 'please fill' +
                            ' this field correctly');
                        textArea.css({
                            'border-color': 'red',
                            'box-shadow': '0 0 17px red'
                        });
                    }
                }
            }).done(function () {
                tweetForm.remove();
            });
        });

        cancelButton.click(function () {
            tweetForm.remove();
        });
    }
    else {
        tweetForm.remove();
    }
}




function updateForm(id) {

    let tweetUpdateForm = $('#tweet-update-form');

    if (!tweetUpdateForm.length) {

        let tweet = $('.tweet-text[data-id=' + id + ']').text();

        let form = $('<form id="tweetForm" ></form>');

        let label = $('<div id="formLabel"><strong>Update tweet</strong></div>');

        let textArea = $('<textarea id="tweetTextarea" name="tweet"></textarea>');

        textArea.val(tweet);

        form.prepend(textArea);
        form.prepend(label);



        let buttonsDiv = $('<div id="buttons-div"></div>');

        let submitButton = $('<button type="submit" class="btn btn-success"' +
            ' id="submitBtn"><i class="fa fa-check" aria-hidden="true" fa-3x></i>' +
            '</button>');

        let cancelButton = $('<button id="cancelBtn" class="btn btn-warning">' +
            '<i class="fa fa-undo" aria-hidden="true" fa-3x></i>' +
            '</button>');

        buttonsDiv.append(submitButton);
        buttonsDiv.append(cancelButton);
        form.append(buttonsDiv);

        let tweetUpdateForm = $('<div id="tweet-update-form"></div>');
        tweetUpdateForm.html(form);

        $('div[data-id=' + id + ']').closest('.tweet-text').prepend(tweetUpdateForm);


        /**********************************************************************/


        submitButton.click(function (event) {
            event.preventDefault();

            let newTweet = textArea.val();

            let data = {
                tweet: newTweet,
                id: id
            };

            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/api/v1/tweet/' + id,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data),
                statusCode: {
                    422: function () {
                        textArea.attr('placeholder', 'please fill' +
                            ' this field correctly');
                        textArea.css({
                            'border-color': 'red',
                            'box-shadow': '0 0 17px red'
                        });
                    }
                }
            }).done(function () {
                tweetUpdateForm.remove();
            });

        });

        cancelButton.click(function () {
            tweetUpdateForm.remove();
        });
    }
    else {
        tweetUpdateForm.remove();
    }
}