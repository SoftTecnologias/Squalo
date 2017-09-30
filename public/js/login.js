
$(function () {
    $('#signin').on('click',function(){
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+""+ "/login",
            type:"POST",
            data:{
                username: $("#username").val(),
                password: $("#password").val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(response){
            if(response.code == 200){
                console.log(response.msg);
                if(response.msg['rol']!= 1 ){
                    //parte de los trabajadores
                    location.reload();
                }
            }else{
                console.log('No se hizo');
                swal("Error",response.msg,response.detail);
            }
        }).fail(function(){
            swal("Error","No pudimos conectarnos al servidor","error");
            console.log('ni conecto');
        });
    });

    $(document).keypress(function(e) {
        if(e.which == 13) {
            $.ajax({
                url:document.location.protocol+'//'+document.location.host+""+ "/login",
                type:"POST",
                data:{
                    username: $("#username").val(),
                    password: $("#password").val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(response){
                if(response.code == 200){
                    console.log(response.msg);
                    if(response.msg['rol']!= 1 ){
                        //parte de los trabajadores
                        location.reload();
                    }
                }else{
                    console.log('No se hizo');
                    swal("Error",response.msg,response.detail);
                }
            }).fail(function(){
                swal("Error","No pudimos conectarnos al servidor","error");
                console.log('ni conecto');
            });

        }
    });
});
