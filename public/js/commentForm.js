function commentForm(id) {

    let commentForm = $('#comment-form');

    if (!commentForm.length) {

        let form = $('<form id="tweetForm" ></form>');

        let label = $('<div id="formLabel"><strong>New comment</strong></div>');

        let textArea = $('<textarea id="tweetTextarea" name="comment"></textarea>');

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


        let commentForm = $('<div id="comment-form"></div>');
        commentForm.html(form);

        $('div[data-id=' + id + ']').parent('.tweet').children('.tweet-icons').append(commentForm);


        /**********************************************************************/


        submitButton.click(function (event) {
            event.preventDefault();

            let newComment = textArea.val();

            let data = {
                comment: newComment
            };

            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/api/v1/tweet/'+ id +'/comment',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data),
                statusCode: {
                    422: function() {
                        textArea.attr('placeholder','please fill' +
                            ' this field correctly');
                        textArea.css({'border-color':'red',
                            'box-shadow':'0 0 17px red'});
                    }}
            }).done(function () {
                commentForm.remove();
            });

        });

        cancelButton.click(function () {
            commentForm.remove();
        });
    }
    else {
        commentForm.remove();
    }
}




function commentUpdateForm(id) {

    let comment = $('.comment-text[data-comment-id=' + id + ']');
    let commentText = comment.text();
    let tweetId = comment.attr('data-tweet-id');

    let commentForm = $('#comment-form');

    if (!commentForm.length) {


        let form = $('<form id="tweetForm" ></form>');

        let label = $('<div id="formLabel"><strong>Update comment</strong></div>');

        let textArea = $('<textarea id="tweetTextarea" name="comment"></textarea>');

        textArea.val(commentText);

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


        let commentForm = $('<div id="tweet-update-form"></div>');
        commentForm.html(form);

        $('.comment-text[data-comment-id=' + id + ']').parent('.comment').prepend(commentForm);


        /**********************************************************************/


        submitButton.click(function (event) {
            event.preventDefault();

            let newComment = textArea.val();

            let data = {
                comment: newComment,
                id: id
            };

            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/api/v1/tweet/'+ tweetId +'/comment/'+ id,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(data),
                statusCode: {
                    422: function() {
                        textArea.attr('placeholder','please fill' +
                            ' this field correctly');
                        textArea.css({'border-color':'red',
                            'box-shadow':'0 0 17px red'});
                    }}
            }).done(function () {

               commentForm.remove();
            });

        });
        cancelButton.click(function () {
            commentForm.remove();
        });
    }
    else {
        commentForm.remove();
    }
}