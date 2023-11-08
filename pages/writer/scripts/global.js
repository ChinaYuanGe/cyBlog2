$(function(){
    window.addEventListener('keydown',function(e){
        if(e.ctrlKey && e.keyCode == 83){
            e.preventDefault();
            saveArtContent();
        }
    });
});