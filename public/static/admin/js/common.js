function del(url){

    layer.confirm('确认要删除吗？',function(index){
        window.location.href=url;
    });
}