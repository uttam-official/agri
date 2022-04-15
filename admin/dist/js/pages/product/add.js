$(document).ready(function(){
    $('#category').on('change',function(){
        const parameter={'category': $('#category').val()};
        $.ajax({
            url:"add.php",
            method:'get',
            data:parameter,
            dataType:'json',
            success:function(data){
                $('#subcategory').empty();
                $('#subcategory').append('<option value disabled selected>---Select a Subcategory---</option>');
                $.each(data,function(i,item){
                    $('#subcategory').append('<option value="'+item.id+'">'+item.name+'</option>');
                })
            },
            error:function(response){
                console.log({"error":response});
            }

            
        });
    });



});