{ //action buttons .addCategory 
    const addCategory = document.querySelectorAll('.addCategory');

    for (i in addCategory) {
        addCategory[i].onclick = function () {
            $('#parent').val($(this).attr('data-id'));
            $('#id').val('');
            $('.modal-title').text('Adicionar sumário');
        }
    }
}

{ //action buttons .editCategory
    const editCategory = document.querySelectorAll('.editCategory');
    for (i in editCategory) {
        editCategory[i].onclick = function () {
            $('#id').val($(this).attr('data-id'));
            $('.modal-title').text('Editar sumário');
        }
    }
}

{//action buttons .btnDeleteCategory
    let btnDeleteCategory = document.querySelectorAll('.deleteCategory');

    for(i in btnDeleteCategory)
    {
        btnDeleteCategory[i].onclick = function()
        {
            if(confirm('Confirme a exclusão aqui.'))
            {
                console.log(this.getAttribute('data-id'))
                deleteCategory(this.getAttribute('data-id'));

                let parent = this.parentNode.parentNode.parentNode.parentNode;

                parent.remove();
            }
            
        }
    }
}

function deleteCategory(idCategory)
{
    return  $.ajax({
        url: "/admin/category/delete",
        method: "POST",
        data: {
            idCategory: idCategory
        }
    });
}