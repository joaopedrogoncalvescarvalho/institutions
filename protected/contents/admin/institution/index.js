{ //action buttons .btnAddInstitution 
    let btnAddInstitution = document.querySelectorAll('.btnAddInstitution');

    for (i in btnAddInstitution) {
        btnAddInstitution[i].onclick = function () {
            { //Carregando dropdownlist #id_city ao clicar do button adicionar instituição
                let selectState = document.querySelector('#id_state');
                let value = selectState.options[selectState.selectedIndex].value;

                loadCities(value, '#id_city');
            }

            { //Adicionando valor ao input text #id_category
                let inputText = document.querySelector('#id_category');
                inputText.value = this.getAttribute('data-id');
            }
        }
    }
}

{ //Carregando dropdownlist #id_city ao mudar de opção em #id_state
    let selectState = document.querySelector('#id_state');

    selectState.onchange = function()
    {
        let value = selectState.options[selectState.selectedIndex].value;

        loadCities(value, '#id_city');
    }
}

function loadCities(idState, tag) 
{
    $.ajax({
        url: "/admin/city/loadByState",
        method: "GET", 
        data: {
            idState: idState
        } 
    }).done(function(result)
    {
        cities = JSON.parse(result);

        let selectCity = document.querySelector(tag);
        selectCity.innerText = document.createTextNode('');

        for (city in cities) {
            let option = document.createElement('option');

            let textHtml = document.createTextNode(cities[city]['name']);

            option.appendChild(textHtml);
            option.value = cities[city]['id'];

            selectCity.appendChild(option);
        }
    })
}

{//action buttons .btnDeleteInstitution
    let btnDeleteInstitution = document.querySelectorAll('.btnDeleteInstitution');

    for(i in btnDeleteInstitution)
    {
        btnDeleteInstitution[i].onclick = function()
        {
            if(confirm('Confirme a exclusão aqui.'))
            {
                deleteInstitution(this.getAttribute('data-id'));

                let parent = this.parentNode.parentNode.parentNode.parentNode;

                parent.remove();
            }
            
        }
    }
}

function deleteInstitution(idInstitution)
{
    return  $.ajax({
        url: "/admin/institution/delete",
        method: "POST",
        data: {
            idInstitution: idInstitution
        }
    });
}

{
    let contents_category = document.querySelectorAll('.move-allowed');

    function dragstart_handler(event)
    {
        // Adiciona o id do elemento alvo para o objeto de transferência de dados
        event.dataTransfer.setData("text/plain", event.target.id);
        event.dropEffect = "move";

        contents_category.forEach(elementCategory => {
            elementCategory.setAttribute('style', 'padding-bottom: 80px;');
        });

    }

    function dragover_handler(event)
    {
        event.preventDefault();
        event.dataTransfer.dropEffect = "move";
    }

    function drop_handler(event) {
        event.preventDefault();

        let dataTransfer = event.dataTransfer.getData("text");

        event.target.appendChild(document.getElementById(dataTransfer));

        let institution = document.getElementById(dataTransfer);

        updateCategory(event.target.getAttribute('data-id'), institution.getAttribute('data-id'));

        contents_category.forEach(elementCategory => {
            elementCategory.setAttribute('style', '');
        });
    }

    function updateCategory(idCategory, idInstitution)
    {
        return  $.ajax({
            url: "/admin/institution/updateCategory",
            method: "POST",
            data: {
                idCategory: idCategory,
                idInstitution: idInstitution
            }
        });
    }
}