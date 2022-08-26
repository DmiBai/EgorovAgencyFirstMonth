const socket = io.connect("http://127.0.0.1:8080/");

socket.on('addMessage', res =>{
    console.log(res.image);
    addMessage(res.name, res.color, res.text, res.image);
})

socket.on('setUsersNumber', res => {
   $('#users-online').text(res);
});
let token = '';
let imageBase64 = '';
document.addEventListener("DOMContentLoaded", function (){

    $('#reg').submit(function(event) {
        event.preventDefault();
        console.log(token);
        ajaxQueryReg('http://localhost:3000/reg');
    });

    $(document).on('click', '#exit-chat', function(event){
        event.preventDefault();
        token = '';
        $('#exit-chat').addClass("d-none");
        $('#join-chat').removeClass("d-none");
        socket.emit('unlog');
    });

    $(document).on('submit','#messForm', function(){
        event.preventDefault();

        if($('#inputGroupFile02').val() !== '') {
            let reg = /.+(\.jpg|\.jpeg|\.png)$/i;

            if(!reg.test($('#inputGroupFile02').val())){
                alert('please use image format');
            } else {
                let imageReader = new FileReader();
                imageReader.readAsDataURL($('#inputGroupFile02').prop('files')[0]);
                imageReader.onloadend = function () {
                    imageBase64 = imageReader.result;
                    
                    if($('#message').val().length > 200){
                        alert('please print message with length less than 200 symbols');
                    } else {
                        let message = {
                            token: token,
                            image: imageBase64,
                            text: $('#message').val()
                        }
                        socket.emit('message', message);
                    }
                }
            }
        }



    });

});

function ajaxQueryReg(url) {
    $.ajax({
        url: url,
        type: 'POST', //метод отправки
        dataType: 'html', //формат данных
        data: { username: $('#name').val(),
            color: $('#color').val()
        },
        success: function (response) {
            let result = $.parseJSON(response);
            console.log(result);
            token = result;
            if(token !== ''){
                socket.emit('log');
                ajaxQueryAuth('http://localhost:3000/auth');
            }
            $('#join-chat').addClass("d-none");
            $('#exit-chat').removeClass("d-none");

            $('.alert-info').addClass("d-none");
            $('#messForm').removeClass("d-none");


        },
        error: function (response) {
            let result = $.parseJSON(response);
            console.log(result);
            $('.alert-info').removeClass("d-none");

        }
    });
}

function ajaxQueryAuth(url) {
    $.ajax({
        url: url,
        type: 'POST', //метод отправки
        dataType: 'html', //формат данных
        data: { token: token },
        success: function (response) {
            let result = $.parseJSON(response);
            console.log(result);
            $('#nickname').text(result);
            $('#messDiv').removeClass("d-none");
        },
        error: function (response) {
        }
    });
}

function addMessage(name, color, text, image){
    let me = '';
    if($('#nickname').text() === name){
        me = 'ml-auto';
    }

    $('#all_mess').append(`<div class='alert alert-`+ color +` col-6'>
                        <p class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-dark">`+ name +`</span>
                            <span class="d-none text-right badge badge-dark">Admin</span>
                        </p>
                        <img style="width: 100px; height: 100px; object-fit: cover;" class="" src=` + image + ` alt="">
                        
                        <p class="mt-2">` + text + `</p>
                        
                    </div>`);
}