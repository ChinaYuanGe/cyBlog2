<script>
    function goLogoff(token){
        ajax_get('/api/logoff.php',{token:token},{
            before:()=>{
                $('#btn_logout').attr('disabled',true);
            },
            success:()=>{
                window.location.href = "/";
            },
            fail:(t)=>{
                bsMKalert('登出失败:'+t,'danger','#alertPlacer',3000);
            },
            always:()=>{
                $('#btn_logout').removeAttr('disabled');
            }
        },6000);
    }
</script>