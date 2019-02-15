<html>
    <head>
        <title>Ebiz | Pelatihan - Codeigniter Datatable</title>
        <?php echo link_tag('public/assets/vendor/bootstrap/css/bootstrap.min.css'); ?>
        <?php echo link_tag('public/assets/vendor/DataTables/datatables.min.css'); ?>
    </head>
    <body>
        <div class="container">
            <h3>DATA KARYAWAN</h3>
            <table id="table" class="table table-striped table-bordered table-hover dt-responsive wrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th>NO HP</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
                </table>
            </div>
        <script src="<?php echo base_url('public/assets/vendor/DataTables/datatables.min.js'); ?>"></script>
        <script type="text/javascript">
            var table;
            $(document).ready(function() {
            
                //datatables
                table = $('#table').DataTable({ 
                    "processing": true, //Feature control the processing indicator.
                    "serverSide": true, //Feature control DataTables' server-side processing mode.
                    "order": [], //Initial no order.
            
                    // Load data for the table's content from an Ajax source
                    "ajax": {
                        "url": "<?php echo site_url('karyawan/ajax_list') ?>",
                        "type": "POST"
                    },
            
                    //Set column definition initialisation properties.
                    "columnDefs": [
                        { 
                            "targets": [ 0 ], //first column / numbering column
                            "orderable": false, //set not orderable
                        },
                    ],    
                });          
            });
        </script>
    </body>
</html>