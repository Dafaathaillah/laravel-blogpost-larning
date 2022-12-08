@extends('layouts.master')

@section('title')
    Halaman Post
@endsection

{{-- @section('content2')
Kategori Page
@endsection --}}

@section('content')
    <h2 class="mb-2 page-title">Post Page</h2>
    <div class="row my-4">
        <!-- Small table -->
        <div class="col-md-12">
            <button type="button" class="btn mb-2 btn-outline-primary" id="btnAdd">Tambah Post</button>
            <div class="card shadow">
                <div class="card-body">
                    <!-- table -->
                    <table class="table datatables" id="table-data">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Post</th>
                                <th>Slug Post</th>
                                <th>User Id</th>
                                <th>Kategori Id</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- simple table -->
    </div>

    <div class="modal fade bd-example-modal-lg" id="modal-data" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="shadow mb-4">
                        <div class="card-header mb-3">
                            <strong class="card-title" id="title-modal"></strong>
                        </div>
                        <div class="container">
                            <form id="form-data" class="needs-validation" novalidate>
                              <input type="hidden" id="id_post">
                              <div class="form-row">
                                 <div class="col-md-12 mb-3">
                                     <label>Judul Post</label>
                                     <input type="text" class="form-control" id="judul_post" name="judul_post"
                                         required>
                                     <div class="valid-feedback"> Looks good! </div>
                                 </div>
                              </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validationSelect2">Kategori</label>
                                        <select class="form-control select2" id="kategori_id" required>
                                            <option>Select Kategori</option>
                                            <optgroup label="List Kategori">
                                             @foreach ($kategoris as $kat )
                                             <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                                             @endforeach
                                            </optgroup>
                                        </select>
                                        <div class="invalid-feedback"> Please select a valid state. </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationSelect2">User</label>
                                        <select class="form-control select2" id="user_id" required>
                                            <option>Select User</option>
                                            <optgroup label="List User">
                                             @foreach ($user as $usr )
                                             <option value="{{ $usr->id_user }}">{{ $usr->name }}</option>
                                             @endforeach
                                            </optgroup>
                                        </select>
                                        <div class="invalid-feedback"> Please select a valid state. </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="btnSave">Simpan</button>
                    <button class="btn btn-warning" id="btnCancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom_js')
    <script>
        $('#dataTable-1').DataTable({
            autoWidth: true,
            "lengthMenu": [
                [5, 50, 100, -1],
                [5, 50, 100, "All"]
            ]
        });
    </script>
    <script>
        var table = $('#table-data').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('post.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'judul_post',
                    name: 'judul_post'
                },
                {
                    data: 'slug_post',
                    name: 'slug_post'
                },
                {
                    data: 'usr.name',
                    name: 'usr.name'
                },
                {
                    data: 'kategori.nama_kategori',
                    name: 'kategori.nama_kategori'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).ready(function() {
            $('body').on('click', '#btnAdd', function() {
                $('#title-modal').html('Form Tambah Post');
                document.getElementById("form-data").reset();
                $('#btnSave').html('Simpan')
                $('#modal-data').modal('show');
            });
        });

        $(document).ready(function() {
            $('body').on('click', '#btnCancel', function() {
                $('#modal-data').modal('hide');
            });
        });

        $(document).ready(function() {
            $('body').on('click', '#btnSave', function() {
                var data = {
                    id: $('#id_post').val(),
                    judul_post: $('#judul_post').val(),
                    kategori_id: $('#kategori_id').val(),
                    user_id: $('#user_id').val(),
                };
                $.ajax({
                    data: data,
                    url: "{{ route('post.save') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger mr-2'
                            },
                            buttonsStyling: false,
                        })
                        console.log(data);
                        table.draw();
                        $('#btnSave').html('Simpan');
                        $('#modal-data').modal('hide');
                        swalWithBootstrapButtons.fire(
                            'Simpan!',
                            'Data Berhasil Disimpan.',
                            'success'
                        )
                    },
                    error: function(data) {
                        console.log(data);
                    },
                });
            });
        });

        $(document).ready(function() {
            $('body').on('click', '#btnEdit', function() {
                $('#title-modal').html('Form Edit Post');
                var ids = $(this).attr("data-id");
                //  console.log(ids);
                var url = "{{ route('post.show', ['post' => ':id']) }}";
                url = url.replace(':id', ids);
                $.get(url, function(response) {
                  //   document.getElementById("nama_kategori").classList.remove("is-invalid");
                    //   document.getElementById("nama_kategoriFeedback").innerHTML = '';

                    document.getElementById("id_post").value = response.id_post;
                    document.getElementById("judul_post").value = response.judul_post;
                    document.getElementById("user_id").value = response.user_id;
                    $('#user_id').trigger('change');
                    document.getElementById("kategori_id").value = response.kategori_id;
                    $('#kategori_id').trigger('change');


                    $('#btnSave').html('Simpan')
                    $('#modal-data').modal('show');
                })

            });
        });

        $(document).ready(function() {
            $('body').on('click', '#btnDelete', function() {
                var dataId = $(this).attr("data-id");
                var data = dataId.split('#');
                //  console.log(dataId)
                //  console.log(data)

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger mr-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Apa kamu Yakin?',
                    text: "Kategori Dengan Nama " + data[1] + " Akan Terhapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'mr-2',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var url = "{{ route('post.delete', ['post' => ':id']) }}"
                        url = url.replace(':id', data[0]);
                        $.ajax({
                            url: url,
                            type: "POST",
                            dataType: 'json',
                            success: function(data) {
                                table.draw();
                                swalWithBootstrapButtons.fire(
                                    'Hapus!',
                                    'Data Berhasil Dihapus.',
                                    'success'
                                )
                            },
                            error: function(data) {
                                table.draw();
                                swalWithBootstrapButtons.fire(
                                    'Gagal',
                                    'Terjadi kesalahan saat menghapus',
                                    'error'
                                )
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Batal',
                            'Data Tidak Diahapus :)',
                            'error'
                        )
                    }
                })
            });
        });
    </script>
@endpush
