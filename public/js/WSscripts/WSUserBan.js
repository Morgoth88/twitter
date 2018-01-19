function banUsr(data) {

    var messages = data.user['messages']
    var comments = data.user['comments']

    for(mess in messages)
    {

       if($('.tweet[data-id='+ messages[mess] +']').length){
           $('.tweet[data-id='+ messages[mess] +']').remove();
       }
    }
    for(comm in comments)
    {
       if($('.comment[data-id='+ comments[comm]+']').length){
           $('.comment[data-id='+ comments[comm] +']').remove();
       }
    }
}

Echo.private('user')
    .listen('.userBan', (data) => {
        banUsr(data)
    });
