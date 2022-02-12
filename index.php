<!DOCTYPE html>
<?php
    include('koneksi.php');
    session_start();
    $user_check = !isset($_SESSION['token']) ? 'false' : $_SESSION['token'];
    
    $ses_sql = mysqli_query($mysqli,"select token from tbl_user where token = '$user_check' ");
    $login_session = false;
    
    
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dedong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
    <style type="text/css">
        .preloader1 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(255,255,255,0.5);
        }
        .loading1 {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
            font: 14px arial;
        }
    </style>
</head>
<body>
    <div id="myloading"></div>
    <div class="container mt-5 mb-5">

        <?php include './login.php'; ?>
        
        <?php include './formuser.php'; ?>

        <div  style="display:block" id="tablesh">
            <div class="row">
                <div class="col-12">
                    <h1>Data Kontak</h1>
                    <div>
                        <button id="createbtn" class="btn btn-sm btn-success">Create</button>
                        <button id="logoutbtn" class="btn btn-sm btn-secondary float-end">Logout</button>
                    </div>
                </div>  
            </div>
            <hr/>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama </th>
                                <th scope="col">Kelas </th>
                                <th scope="col">Jurusan </th>
                                <th scope="col">Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- List Data Menggunakan DataTable -->   
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            <hr/>
        </div>
    </div>
</body>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var method = "add";
        var table;
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        $(document).ready( function () {
            if(<?= !isset($_SESSION['token']) ? 'false' : 'true' ?> == false){
                var z = document.getElementById("formlg");
                var x = document.getElementById("formau");
                var y = document.getElementById("tablesh");
                z.style.display = "block";
                x.style.display = "none";
                y.style.display = "none";
            }else{
                var z = document.getElementById("formlg");
                var x = document.getElementById("formau");
                var y = document.getElementById("tablesh");
                z.style.display = "none";
                x.style.display = "none";
                y.style.display = "block";
            }

            table = $('.table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax":{
                        "url": "ajax_kontak.php",
                        "data": {action: "table_data"},
                        "dataType": "json",
                        "type": "POST"
                        },
                "columns": [
                    { "data": "no" },
                    { "data": "nama" },
                    { "data": "kelas" },
                    { "data": "jurusan" },
                    { "data": "aksi" },
                ]  
            });

            $(document).on('click', '#createbtn', function() {
                method = "add";
                $("#form").trigger("reset");
                // $('#exampleModal').modal('show');
                var x = document.getElementById("formau");
                var y = document.getElementById("tablesh");
                if (x.style.display === "none") {
                    x.style.display = "block";
                    y.style.display = "none";
                } else {
                    x.style.display = "none";
                    y.style.display = "block";
                }
            });

            $(document).on('click', '#btback', function() {
                var x = document.getElementById("formau");
                var y = document.getElementById("tablesh");
                if (x.style.display === "none") {
                    x.style.display = "block";
                    y.style.display = "none";
                } else {
                    x.style.display = "none";
                    y.style.display = "block";
                }
            });

            $(document).on('click', '#logoutbtn', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You Wanna Logout!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, i want to log out!'
                    }).then((result) => {
                        $.ajax({
                            url: "ajax_kontak.php?actionlgout=logout",
                            type: "POST",
                            dataType: "json",
                            success: function (res) {
                                var z = document.getElementById("formlg");
                                var x = document.getElementById("formau");
                                var y = document.getElementById("tablesh");
                                z.style.display = "block";
                                x.style.display = "none";
                                y.style.display = "none";
                            }
                        });
                    })
            });

            $(document).on('submit', '#form', function(e) {
                e.preventDefault();
                $('#myloading').html('<div class="preloader1"><div class="loading1"><div class="spinner-border" role="status"></div></div></div>');
                let url;
                if(method == "add"){
                    url = "ajax_kontak.php?action=add_data";
                }else{
                    url = "ajax_kontak.php?actions=update_data";
                }
                $.ajax({
                    url: url,
                    type: "POST",
                    data: $("#form").serialize(),
                    dataType: "json",
                    success: function (res) {
                        $('#myloading').html('');
                        if(res.status == true){
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                            var x = document.getElementById("formau");
                            var y = document.getElementById("tablesh");
                            if (x.style.display === "none") {
                                x.style.display = "block";
                                y.style.display = "none";
                            } else {
                                x.style.display = "none";
                                y.style.display = "block";
                            }
                            // $('#exampleModal').modal('hide');
                            table.ajax.reload();
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: res.message
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#myloading').html('');
                        Toast.fire({
                            icon: 'error',
                            title: 'Server Error Please Try Again Later!!!!'
                        });
                    }
                });
            });
            
            $(document).on('submit', '#formlgn', function(e) {
                e.preventDefault();
                $('#myloading').html('<div class="preloader1"><div class="loading1"><div class="spinner-border" role="status"></div></div></div>');
                $.ajax({
                    url: "ajax_kontak.php?actionlog=login",
                    type: "POST",
                    data: $("#formlgn").serialize(),
                    dataType: "json",
                    success: function (res) {
                        $('#myloading').html('');
                        $("#formlgn").trigger("reset");
                        if(res.status == true){
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                            document.getElementById('DataTables_Table_0').style.width = '1299px';
                            var z = document.getElementById("formlg");
                            var x = document.getElementById("formau");
                            var y = document.getElementById("tablesh");
                            z.style.display = "none";
                            x.style.display = "none";
                            y.style.display = "block";
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: res.message
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#myloading').html('');
                        Toast.fire({
                            icon: 'error',
                            title: 'Server Error Please Try Again Later!!!!'
                        });
                    }
                });
            });

        });

        function update_siswa(id){
            $('#myloading').html('<div class="preloader1"><div class="loading1"><div class="spinner-border" role="status"></div></div></div>');
            method = "update";
            $.ajax({
                url: "ajax_kontak.php",
                type: "POST",
                data: {id : id, actions: "show"},
                dataType: "json",
                success: function (res) {
                    var x = document.getElementById("formau");
                    var y = document.getElementById("tablesh");
                    if (x.style.display === "none") {
                        x.style.display = "block";
                        y.style.display = "none";
                    } else {
                        x.style.display = "none";
                        y.style.display = "block";
                    }
                    $('#myloading').html('');
                    $('#inputid').val(res.id);
                    $('#inputnama').val(res.nama);
                    $('#inputkelas').val(res.kelas);
                    $('#inputjurusan').val(res.jurusan);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#myloading').html('');
                    Toast.fire({
                        icon: 'error',
                        title: 'Server Error Please Try Again Later!!!!'
                    });
                }
            });
        }

        function delete_siswa(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#myloading').html('<div class="preloader1"><div class="loading1"><div class="spinner-border" role="status"></div></div></div>');
                    $.ajax({
                        url: "ajax_kontak.php",
                        type: "POST",
                        data: {id : id, actiond: "delete"},
                        dataType: "json",
                        success: function (res) {
                            $('#myloading').html('');
                            if(res.status == true){
                                Swal.fire(
                                    'Deleted!',
                                    res.message,
                                    'success'
                                );
                                table.ajax.reload();
                            }else{
                                Swal.fire(
                                    'Server Error!',
                                    res.message,
                                    'error'
                                );
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#myloading').html('');
                            Toast.fire({
                                icon: 'error',
                                title: 'Server Error Please Try Again Later!!!!'
                            });
                        }
                    });
                }
            })
        }
    </script>
</html>

<!-- 
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form">
            <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="hidden" name="id" id="inputid" >
                        <input type="text" name="nama" id="inputnama" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" name="kelas" id="inputkelas" class="form-control" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select type="text" name="jurusan" id="inputjurusan" class="form-control">
                            <option value="">--- Please Select ---</option>
                            <option value="RPL">Rekayasa Perangkat Lunak</option>
                            <option value="TKJ">Teknik Komputer Jaringan</option>
                            <option value="MM">Multimedia</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div> -->