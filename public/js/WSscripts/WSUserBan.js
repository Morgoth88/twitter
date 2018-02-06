
function banPosts (posts, elementClass) {
    for (let post in posts){

        let postInDom = $('.' + elementClass + '[data-id=' + posts[post].id + ']');

        if(postInDom.length){
            postInDom.remove();
        }
    }
}

Echo.private('user')
    .listen('.userBan', (data) => {

        banPosts(data.user['messages'],'tweet');
        banPosts(data.user['comments'],'comment')
    });
