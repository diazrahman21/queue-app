<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Antrian Stand Pameran</title>
    
    <!-- Bootstrap 3 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
    
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        .container {
            background-color: white;
            border-radius: 5px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .page-title {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        .form-section {
            margin-bottom: 40px;
        }
        .tab-content {
            margin-top: 20px;
        }
        .ticket-card {
            border: 3px solid #333;
            padding: 30px;
            border-radius: 10px;
            background-color: #f9f9f9;
            text-align: center;
            max-width: 400px;
            margin: 20px auto;
        }
        .ticket-stand-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
        }
        .ticket-nomor {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }
        .ticket-info {
            text-align: left;
            margin-top: 20px;
            font-size: 14px;
        }
        .ticket-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }
        .ticket-info-label {
            font-weight: bold;
        }
        .alert-success-ticket {
            margin-bottom: 20px;
        }
        .download-buttons {
            margin-top: 20px;
        }
        .nav-tabs > li > a {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Title -->
        <h1 class="page-title">
            <i class="glyphicon glyphicon-calendar"></i> Sistem Antrian Stand Pameran
        </h1>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#booking-form" aria-controls="booking-form" role="tab" data-toggle="tab">
                    <i class="glyphicon glyphicon-edit"></i> Buat Pesanan
                </a>
            </li>
            <li role="presentation">
                <a href="#booking-list" aria-controls="booking-list" role="tab" data-toggle="tab">
                    <i class="glyphicon glyphicon-list"></i> Daftar Pesanan
                </a>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content">
            <!-- Tab 1: Booking Form -->
            <div role="tabpanel" class="tab-pane active" id="booking-form">
                <div class="form-section">
                    <h3>Formulir Pemesanan</h3>
                    
                    <!-- Alert Messages -->
                    <div id="booking-alert"></div>

                    <form id="bookingForm" class="form-horizontal">
                        <!-- Stand Selection -->
                        <div class="form-group">
                            <label for="kd_stand" class="col-sm-2 control-label">Pilih Stand <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select id="kd_stand" name="kd_stand" class="form-control" required>
                                    <option value="">-- Pilih Stand --</option>
                                    <option value="FT">Foto (50 kuota/hari)</option>
                                    <option value="LK">Lukis (30 kuota/hari)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Name Input -->
                        <div class="form-group">
                            <label for="nama" class="col-sm-2 control-label">Nama Pemesan <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
                            </div>
                        </div>

                        <!-- Date Picker -->
                        <div class="form-group">
                            <label for="tanggal_pesan" class="col-sm-2 control-label">Pilih Tanggal <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tanggal_pesan" name="tanggal_pesan" placeholder="Pilih tanggal pemesanan" required readonly>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="glyphicon glyphicon-ok"></i> Buat Pesanan
                                </button>
                                <button type="reset" class="btn btn-default btn-lg">
                                    <i class="glyphicon glyphicon-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Ticket Display (Hidden by default) -->
                <div id="ticketContainer" style="display: none;">
                    <div class="alert alert-success alert-success-ticket">
                        <strong><i class="glyphicon glyphicon-ok-circle"></i> Pesanan Anda berhasil dibuat!</strong>
                    </div>
                    
                    <div class="ticket-card" id="ticketCard">
                        <div class="ticket-stand-name" id="ticketStandName"></div>
                        <div class="ticket-nomor" id="ticketNomor"></div>
                        <div class="ticket-info">
                            <div class="ticket-info-row">
                                <span class="ticket-info-label">Nama:</span>
                                <span id="ticketNama"></span>
                            </div>
                            <div class="ticket-info-row">
                                <span class="ticket-info-label">Email:</span>
                                <span id="ticketEmail"></span>
                            </div>
                            <div class="ticket-info-row">
                                <span class="ticket-info-label">Tanggal:</span>
                                <span id="ticketTanggal"></span>
                            </div>
                        </div>
                    </div>

                    <div class="download-buttons text-center">
                        <button type="button" class="btn btn-info btn-lg" id="downloadPdfBtn">
                            <i class="glyphicon glyphicon-download"></i> Download as PDF
                        </button>
                        <button type="button" class="btn btn-warning btn-lg" id="downloadJpgBtn">
                            <i class="glyphicon glyphicon-picture"></i> Download as JPG
                        </button>
                        <button type="button" class="btn btn-default btn-lg" id="resetFormBtn">
                            <i class="glyphicon glyphicon-plus"></i> Buat Pesanan Lagi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Booking List (Admin Table) -->
            <div role="tabpanel" class="tab-pane" id="booking-list">
                <h3>Daftar Semua Pesanan</h3>
                <div class="table-responsive" style="margin-top: 20px;">
                    <table id="bookingTable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal</th>
                                <th>Stand</th>
                                <th>Nomor Antri</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- Bootstrap 3 JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
    
    <!-- html2canvas for image download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <!-- jsPDF for PDF download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize jQuery UI Datepicker
            $('#tanggal_pesan').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                showButtonPanel: true,
                firstDay: 1
            });

            // Initialize DataTable
            var bookingTable = $('#bookingTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/antrian',
                    dataSrc: function(json) {
                        return json.data || [];
                    }
                },
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'nama' },
                    { data: 'email' },
                    {
                        data: 'tanggal_pesan',
                        render: function(data) {
                            return formatDate(data);
                        }
                    },
                    {
                        data: 'kd_stand',
                        render: function(data, type, row) {
                            var standName = data === 'FT' ? 'Foto' : (data === 'LK' ? 'Lukis' : data);
                            return '<span class="label label-info">' + standName + '</span>';
                        }
                    },
                    {
                        data: 'nomor_antri',
                        render: function(data) {
                            return '<strong>' + data + '</strong>';
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return '<button class="btn btn-xs btn-danger delete-btn" data-id="' + data + '" title="Hapus pesanan">' +
                                '<i class="glyphicon glyphicon-trash"></i> Hapus</button>';
                        }
                    }
                ],
                order: [[3, 'desc']],
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                }
            });

            // Reload table when tab is clicked
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                if ($(e.target).attr('href') === '#booking-list') {
                    bookingTable.ajax.reload();
                }
            });

            // Handle booking form submission
            $('#bookingForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    nama: $('#nama').val(),
                    email: $('#email').val(),
                    tanggal_pesan: $('#tanggal_pesan').val(),
                    kd_stand: $('#kd_stand').val()
                };

                var submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="glyphicon glyphicon-hourglass"></i> Memproses...');

                $.ajax({
                    url: '/api/antrian',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show ticket
                            var booking = response.data;
                            var standsMap = {
                                'FT': 'Foto',
                                'LK': 'Lukis'
                            };

                            $('#ticketStandName').text(standsMap[booking.kd_stand] || booking.kd_stand);
                            $('#ticketNomor').text(booking.nomor_antri);
                            $('#ticketNama').text(booking.nama);
                            $('#ticketEmail').text(booking.email);
                            $('#ticketTanggal').text(formatDate(booking.tanggal_pesan));

                            // Hide form, show ticket
                            $('#bookingForm').fadeOut(function() {
                                $('#ticketContainer').fadeIn();
                            });
                            
                            $('#booking-alert').html('');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        submitBtn.html('<i class="glyphicon glyphicon-ok"></i> Buat Pesanan');

                        var errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            errorMsg = Object.values(errors).map(e => e[0]).join('<br>');
                        }

                        $('#booking-alert').html(
                            '<div class="alert alert-danger alert-dismissible">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<i class="glyphicon glyphicon-exclamation-sign"></i> ' + errorMsg +
                            '</div>'
                        );
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html('<i class="glyphicon glyphicon-ok"></i> Buat Pesanan');
                    }
                });
            });

            // Handle reset form after ticket display
            $('#resetFormBtn').on('click', function() {
                $('#bookingForm')[0].reset();
                $('#ticketContainer').fadeOut(function() {
                    $('#bookingForm').fadeIn();
                });
            });

            // Handle delete button
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                
                if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                    $.ajax({
                        url: '/api/antrian/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                bookingTable.ajax.reload();
                                alert('Pesanan berhasil dihapus');
                            }
                        },
                        error: function() {
                            alert('Gagal menghapus pesanan');
                        }
                    });
                }
            });

            // Download as PDF
            $('#downloadPdfBtn').on('click', function() {
                var element = document.getElementById('ticketCard');
                html2canvas(element, { scale: 2 }).then(function(canvas) {
                    var imgData = canvas.toDataURL('image/png');
                    var pdf = new jspdf.jsPDF();
                    var imgWidth = 200;
                    var imgHeight = (canvas.height * imgWidth) / canvas.width;
                    pdf.addImage(imgData, 'PNG', 5, 5, imgWidth, imgHeight);
                    pdf.save('tiket-antrian.pdf');
                });
            });

            // Download as JPG
            $('#downloadJpgBtn').on('click', function() {
                var element = document.getElementById('ticketCard');
                html2canvas(element, { scale: 2 }).then(function(canvas) {
                    var link = document.createElement('a');
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.download = 'tiket-antrian.jpg';
                    link.click();
                });
            });

            // Utility function to format date
            function formatDate(dateString) {
                var date = new Date(dateString);
                var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                             'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                var dayName = days[date.getDay()];
                var day = date.getDate();
                var monthName = months[date.getMonth()];
                var year = date.getFullYear();
                
                return dayName + ', ' + day + ' ' + monthName + ' ' + year;
            }
        });
    </script>
</body>
</html>
