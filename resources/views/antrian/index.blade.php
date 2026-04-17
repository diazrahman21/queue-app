<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Antrian Stand Pameran</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
    
    <style>
        body { background-color: #f5f5f5; padding: 20px 0; }
        .container { background-color: white; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 40px; }
        .page-title { margin-bottom: 30px; border-bottom: 2px solid #0275d8; padding-bottom: 15px; }
        .ticket-card { border: 3px solid #333; padding: 30px; text-align: center; max-width: 400px; margin: 20px auto; background: #f9f9f9; }
        .ticket-nomor { font-size: 48px; font-weight: bold; letter-spacing: 2px; margin: 20px 0; font-family: 'Courier New'; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title"><i class="glyphicon glyphicon-calendar"></i> Sistem Antrian Stand Pameran</h1>

        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#form-tab" data-toggle="tab"><i class="glyphicon glyphicon-edit"></i> Buat Pesanan</a></li>
            <li><a href="#list-tab" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> Daftar Pesanan</a></li>
        </ul>

        <div class="tab-content" style="margin-top: 20px;">
            <!-- Tab 1: Form -->
            <div id="form-tab" class="tab-pane active">
                <h3>Formulir Pemesanan</h3>
                <div id="alert-box"></div>

                <form id="form" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2">Stand *</label>
                        <div class="col-sm-10">
                            <select id="kd_stand" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="FT">Foto (50/hari)</option>
                                <option value="LK">Lukis (30/hari)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2">Nama *</label>
                        <div class="col-sm-10">
                            <input type="text" id="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2">Email *</label>
                        <div class="col-sm-10">
                            <input type="email" id="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2">Tanggal *</label>
                        <div class="col-sm-10">
                            <input type="text" id="tanggal" class="form-control" readonly required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                <i class="glyphicon glyphicon-ok"></i> Buat Pesanan
                            </button>
                            <button type="reset" class="btn btn-default btn-lg">Reset</button>
                        </div>
                    </div>
                </form>

                <!-- Ticket -->
                <div id="ticket-box" style="display:none;">
                    <div class="alert alert-success">
                        <strong>✓ Pesanan berhasil dibuat!</strong>
                    </div>
                    <div class="ticket-card" id="ticket-card">
                        <div style="font-size: 24px; font-weight: bold; color: #0275d8;" id="stand-name"></div>
                        <div class="ticket-nomor" id="nomor-antri"></div>
                        <table style="margin-top: 20px; text-align: left; width: 100%;">
                            <tr>
                                <td style="font-weight: bold;">Nama:</td>
                                <td id="t-nama"></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Email:</td>
                                <td id="t-email"></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Tanggal:</td>
                                <td id="t-tanggal"></td>
                            </tr>
                        </table>
                    </div>
                    <div style="text-align: center;">
                        <button class="btn btn-info btn-lg" id="pdf-btn"><i class="glyphicon glyphicon-download"></i> PDF</button>
                        <button class="btn btn-warning btn-lg" id="jpg-btn"><i class="glyphicon glyphicon-picture"></i> JPG</button>
                        <button class="btn btn-default btn-lg" id="reset-ticket"><i class="glyphicon glyphicon-plus"></i> Pesanan Baru</button>
                    </div>
                </div>
            </div>

            <!-- Tab 2: List -->
            <div id="list-tab" class="tab-pane">
                <h3>Daftar Pesanan</h3>
                <table id="table" class="table table-striped table-bordered" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal</th>
                            <th>Stand</th>
                            <th>No. Antri</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
    
    <!-- html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // Global function for formatting dates
        function formatDate(dateStr) {
            var date = new Date(dateStr);
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return days[date.getDay()] + ', ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
        }

        $(document).ready(function() {
            console.log('Document ready, jQuery version:', $.fn.jquery);

            // Initialize datepicker
            $('#tanggal').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0
            });

            // Initialize DataTable
            console.log('Initializing DataTable...');
            var table = $('#table').DataTable({
                ajax: {
                    url: '/api/antrian',
                    type: 'GET',
                    dataSrc: function(json) {
                        console.log('DataTable ajax response:', json);
                        return json.data || [];
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTable ajax error:', error, thrown);
                    }
                },
                columns: [
                    { data: null, render: function(data, type, row, meta) { return meta.row + 1; } },
                    { data: 'nama' },
                    { data: 'email' },
                    { data: 'tanggal_pesan', render: function(d) { return formatDate(d); } },
                    { data: 'kd_stand', render: function(d) { return d === 'FT' ? 'Foto' : 'Lukis'; } },
                    { data: 'nomor_antri', render: function(d) { return '<strong>' + d + '</strong>'; } },
                    { data: 'id', render: function(d) { return '<button class="btn btn-xs btn-danger hapus" data-id="' + d + '"><i class="glyphicon glyphicon-trash"></i></button>'; } }
                ],
                language: { emptyTable: 'Tidak ada data' }
            });

            // Reload table on tab click
            $('a[href="#list-tab"]').on('click', function() {
                setTimeout(function() {
                    table.ajax.reload();
                }, 100);
            });

            // Form submit
            $('#form').on('submit', function(e) {
                e.preventDefault();
                
                var data = {
                    nama: $('#nama').val().trim(),
                    email: $('#email').val().trim(),
                    tanggal_pesan: $('#tanggal').val().trim(),
                    kd_stand: $('#kd_stand').val().trim()
                };

                console.log('Submitting:', data);

                var btn = $('#submit-btn').prop('disabled', true).text('Proses...');

                $.ajax({
                    url: '/api/antrian',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        console.log('Success:', res);
                        if (res.success) {
                            var b = res.data;
                            $('#stand-name').text(b.kd_stand === 'FT' ? 'Foto' : 'Lukis');
                            $('#nomor-antri').text(b.nomor_antri);
                            $('#t-nama').text(b.nama);
                            $('#t-email').text(b.email);
                            $('#t-tanggal').text(formatDate(b.tanggal_pesan));
                            
                            $('#form').fadeOut(function() {
                                $('#ticket-box').fadeIn();
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.status, xhr.responseJSON);
                        var msg = 'Gagal!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errs = [];
                            $.each(xhr.responseJSON.errors, function(k, v) {
                                errs.push(Array.isArray(v) ? v[0] : v);
                            });
                            msg = errs.join(', ');
                        }
                        $('#alert-box').html('<div class="alert alert-danger alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>' + msg + '</div>');
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Buat Pesanan');
                    }
                });
            });

            // Reset ticket
            $('#reset-ticket').on('click', function() {
                $('#form')[0].reset();
                $('#ticket-box').fadeOut(function() {
                    $('#form').fadeIn();
                });
            });

            // Delete booking
            $(document).on('click', '.hapus', function() {
                if (!confirm('Hapus?')) return;
                
                var id = $(this).data('id');
                $.ajax({
                    url: '/api/antrian/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        table.ajax.reload();
                    }
                });
            });

            // Download PDF
            $('#pdf-btn').on('click', function() {
                html2canvas(document.getElementById('ticket-card')).then(function(canvas) {
                    var pdf = new jspdf.jsPDF();
                    var img = canvas.toDataURL('image/png');
                    pdf.addImage(img, 'PNG', 10, 10, 190, 270);
                    pdf.save('tiket.pdf');
                });
            });

            // Download JPG
            $('#jpg-btn').on('click', function() {
                html2canvas(document.getElementById('ticket-card')).then(function(canvas) {
                    var link = document.createElement('a');
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.download = 'tiket.jpg';
                    link.click();
                });
            });
        });
    </script>
</body>
</html>
