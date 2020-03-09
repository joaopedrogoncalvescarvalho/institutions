$('.addInstitution').click(function()
{
    $('.card-title').text('Adicionar instituição');

    loadDropDownCities(1);

    console.log($(this).attr('data-id_category'));

    $('#id').val('');
    $('#id_category').val($(this).attr('data-id_category'));
    $('#indice').val('');
    $('.id_nature').prop('CHECKED', false);
    $('#name').val('');
    $('#name_of_responsible_of_institution').val('');
    $('#name_of_responsible_of_social_area').val('');
    $('#email').val('');
    $('#phone').val('');
    $('#coverage_area').val('');
    $('#address').val('');
    $('#id_state').val(1);
    $('#id_city').val(1);
});

function loadDropDownCities(id_state)
{
    $.get('/admin/city/loadByState/' + id_state, function(result)
    {
        let cities = JSON.parse(result);

        let html = '';

        cities.forEach(row => {
            html += '<option value="' + row['id'] + '">' + row['name'] + '</option>';
        });

        return html;
    });
}

$('.editInstitution').click(function()
{
    $('.card-title').text('Editar dados da instituição');

    $('#id').val($(this).attr('data-id'));

    loadDropDownCities($('#id_state'));

    $.get('/admin/institution/loadFull/' + $(this).attr('data-id'), function(result)
    {
        let institution = JSON.parse(result);

        institution.forEach(function(row)
        {
            for(item in row)
            {
                if($($('.' + item)).attr('type') == 'radio')
                {
                    if(item == 'id_nature')
                    {
                        $('#' + item + '_' + row[item]).prop('checked',true);
                    }
                }
                else if($($('#' + item)).prop("tagName") == 'SELECT')
                {
                    $('#' + item).val(row[item]);
                }
                else
                {
                    $('#' + item).val(row[item]);
                }
            }
        });
    });
});

$('.icon-remove').click(function()
{
    if(confirm('Deseja remover essa categoria e suas subcategorias'))
    {
        window.location.href = '/admin/institution/delete/' + $(this).attr('data-id');
    }
});

$('#id_state').change(function()
{
    if($('#id_state').val() != '')
        loadDropDownCities($('#id_state').val());
});