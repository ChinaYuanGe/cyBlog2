<script>
    function goAuth(password){
        let md5pass = md5(password);
        ajax_post("/api/auth.php",{passmd5:md5pass},{
            before:()=>{
                $('#btn_login').attr("disabled",true);
                $('#passmd5').attr('disabled',true);
            },
            success:(data)=>{
                let jobj = JSON.parse(data);
                let jobj_data = jobj['data'];
                if(jobj['status'] == "ok"){
                    $.cookie('token',jobj_data['token'],{expires:1});
                    bsMKalert('登录成功','success','#alertPlacer',3000);
                    setTimeout(()=>{window.location.href="/"},500);
                }
                else if(jobj['status'] == "fail"){
                    bsMKalert('验证失败','danger','#alertPlacer',3000);
                }
            },
            fail:(t)=>{
                bsMKalert('请求失败:'+t,'danger','#alertPlacer',3000);
            },
            always:()=>{
                $('#btn_login').removeAttr("disabled");
                $('#passmd5').removeAttr("disabled");
            }
        },6000);
    }
</script>