var table, pKey;
var tbl = '#table tbody';
$(document).ready(function () {
    table = $('#table').DataTable({
        language: {
			"info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
			"searchPlaceholder": 'kata kunci'
		},
        "processing": true, 
        "serverSide": true, 
        "order": [], 

        "ajax": {
            "url": 'home/ajax_list',
            "type": "POST"
        },

        "columnDefs": [
            {
                "targets": [0], 
                "visible": false, 
            },
            {
                "targets": -1,
                "data": null,
                "defaultContent":
                '<button title="Edit" style="margin-right: 5px;" class="btEdit btn btn-warning btn-xs" type="button"><i class="fa fa-pencil-square-o"></i> Edit</button>' + 
                '<button title="Delete" class="btDelete btn btn-danger btn-xs delete" type="button"><i class="fa fa-trash-o"></i> Delete</button>'
            }
        ],
    });

    /* DataTable search on enter */
    $('#table_filter input').unbind();
    $('#table_filter input').bind('keyup', function(event) {
        let val = this.value;
        let len = val.length;
        if (event.keyCode === 13 && len > 0) table.search(this.value).draw()
        if (len > 0) enterBackspace = true;
        if (enterBackspace) {
            if (event.keyCode == 8 && len == 0) {
                table.search(this.value).draw();
                enterBackspace = false;
            }
        }
    });

    // Btn Create [+]
    $('#btnCreate').on('click', function(){
        $('.formEditorModal form')[0].reset();
        $('.btn_save').html('<i class="fa fa-save"></i> Save');
        $('.formEditorModal').modal();
        console.log($('.btn_save').html());
    });

    // Btn Edit
    $(tbl).on('click', '.btEdit', function () {
        let data = (table.row($(this).closest('tr')).data() === undefined) ? table.row($(this).closest('li')).data() : table.row($(this).closest('tr')).data();
        pKey = data[0];

        /* Set Edit Form Value */
        $("input[name=nama_lengkap]").val(data[2]);
        $("input[name=email]").val(data[3]);
        $("input[name=no_hp]").val(data[4]);
        $('.btn_save').html('<i class="fa fa-save"></i> Update');
        $('.modal-title').html('Form Edit');
        $('.formEditorModal').modal();
    });

    // Btn Delete [-]
    $(tbl).on('click', '.btDelete', function () {
        let data = (table.row($(this).closest('tr')).data() === undefined) ? table.row($(this).closest('li')).data() : table.row($(this).closest('tr')).data();
        pKey = data[0];
        if (confirm('Are you sure you want to save this thing into the database?')) {
            let postData = {'id_karyawan': pKey};
            $.ajax({
                "type": 'POST',
                "url": 'home/delete',
                "data": postData,
                "dataType": 'json',
                "success": function (result, textStatus, jqXHR) {
                    if (result.success === true) {
                        table.ajax.reload(null, false);
                        alert('deleted');
                    }else {
                        alert('penyimpanan gagal\n' + result.message);
                    }
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }
    });

    // Btn Save
    $('.btn_save').on('click', function(){
        let url;
		var formData = $('#formEditor').serializeArray();
        $('.btn_save').button('loading');
        $(this.element).prop('disabled', true);
        
        console.log($(this).html());
        if ( $('.btn_save').html() == '<i class="fa fa-save"></i> Save' ) {
            url = 'home/create';
        } else if( $('.btn_save').html() == '<i class="fa fa-save"></i> Update' ) {
            url = 'home/update';
            formData.push({name: 'id_karyawan', value: pKey});
        }

        $.ajax({
            "type": 'POST',
            "url": url,
            "data": formData,
            "dataType": 'json',
            "success": function (result, textStatus, jqXHR) {
                if (result.success === true) {
                    table.ajax.reload(null, false);
                    alert('data tersimpan');
                }else {
                    alert('penyimpanan gagal\n' + result.message);
                }
                $('.formEditorModal').modal('hide');
                $('.btn_save').button('reset');
                $(this.element).prop('disabled', false);
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                $('.btn_save').button('reset');
                $(this.element).prop('disabled', false);
                alert(errorThrown);
                $('.formEditorModal').modal('hide');
                $('.btn_save').button('reset');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    });
});


