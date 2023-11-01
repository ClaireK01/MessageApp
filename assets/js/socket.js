import * as $ from 'jquery'

var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    let mess = JSON.parse(e.data)['msg'];
    let from = JSON.parse(e.data)['isFrom'];
    let old = $('#message-cont').html();
    $.ajax({
        url : $('#message-cont').data('url'),
        method: 'POST',
        data: {'mess' : mess, 'old' : old, 'from' : from},
        success : function (json){
            $('#message-cont').html(json.view)
        }
    })
};

$('.btn-send').on('click', function (e){
    let mess = $('#input-sender').val();
    $('#input-sender')[0].value = '';
    conn.send(mess);

})



