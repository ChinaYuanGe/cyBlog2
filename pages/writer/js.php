<script src="/resources/tinymce/tinymce.min.js"></script>

<script>
    artID = <?php echo $routerInfo[2] ?>;

    //定时
    $(function(){
        setInterval(()=>{
            if(global_saved == false){
                saveArtContent(()=>{
                    bsMKalert('已自动保存内容','success','#alertHolder',3000);
               },false);
            }
        },60 * 1000);
    });
</script>