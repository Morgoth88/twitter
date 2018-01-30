function banUsr(data) {

    var messages = data.user['messages']
    var comments = data.user['comments']

    for(mess in messages)
    {

       if($('.tweet[data-id='+ messages[mess].id +']').length){
           $('.tweet[data-id='+ messages[mess].id +']').remove();
       }
    }

    for(comm in comments)
    {
       if($('.comment[data-id='+ comments[comm].id+']').length){
           $('.comment[data-id='+ comments[comm].id +']').remove();
       }
    }
}

Echo.private('user')
    .listen('.userBan', (data) => {
        banUsr(data)
    });
