<script>
    $(document).ready(function() {
        $('#departamento').on('change', function() {
            var departamentoId = $(this).val();

            if(departamentoId) {
                $.ajax({
                    url: '/municipios/' + departamentoId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#municipio').empty();
                        $('#municipio').append('<option value="">Seleccione un municipio</option>');

                        $.each(data, function(key, value) {
                            $('#municipio').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                    }
                });
            } else {
                $('#municipio').empty();
                $('#municipio').append('<option value="">Primero seleccione un departamento</option>');
            }
        });
    });
</script>
