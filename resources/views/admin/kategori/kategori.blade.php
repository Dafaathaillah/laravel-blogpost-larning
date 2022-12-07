@extends('layouts.master')

@section('title')
    Halaman Kategori
@endsection

{{-- @section('content2')
Kategori Page
@endsection --}}

@section('content')
    <h2 class="mb-2 page-title">Kategori Page</h2>
    <div class="row my-4">
        <!-- Small table -->
        <div class="col-md-12">
            <button type="button" class="btn mb-2 btn-outline-primary" id="btnAdd">Tambah Kategori</button>
            <div class="card shadow">
                <div class="card-body">
                    <!-- table -->
                    <table class="table datatables" id="tableKategori">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name of Kategori</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- simple table -->
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalAddKategori" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="shadow mb-4">
                        <div class="card-header mb-3">
                            <strong class="card-title" id="title-modal"></strong>
                        </div>
                        <div class="container">
                            <form id="formKategori" class="needs-validation" novalidate>
                                <input type="hidden" id="id_kategori">
                                <div class="mb-3">
                                    <label>Nama Kategori</label>
                                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                        required>
                                    <div class="valid-feedback"> Looks good! </div>
                                </div> <!-- /.form-row -->
                            </form> <!-- /.card-body -->
                            <button class="btn btn-primary" id="btnSaveKategori">Send</button>
                        </div>
                    </div>
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
        var table = $('#tableKategori').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('kategori.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori'
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
               $('#title-modal').html('Form Tambah Kategori');
                document.getElementById("formKategori").reset();
                $('#btnSaveKategori').html('Simpan')
                $('#modalAddKategori').modal('show');
            });
        });


        $(document).ready(function() {
            $('body').on('click', '#btnEdit', function() {
               $('#title-modal').html('Form Edit Kategori');
                var ids = $(this).attr("data-id");
                //  console.log(ids);
                var url = "{{ route('kategori.show', ['kategori' => ':id']) }}";
                url = url.replace(':id', ids);
                $.get(url, function(response) {
                    document.getElementById("nama_kategori").classList.remove("is-invalid");
                    //   document.getElementById("nama_kategoriFeedback").innerHTML = '';

                    document.getElementById("id_kategori").value = response.id_kategori;
                    document.getElementById("nama_kategori").value = response.nama_kategori;


                    $('#btnSaveKategori').html('Simpan')
                    $('#modalAddKategori').modal('show');
                })

            });
        });

        $(document).ready(function() {
            $('body').on('click', '#btnSaveKategori', function() {
                var data = {
                    id: $('#id_kategori').val(),
                    nama_kategori: $('#nama_kategori').val(),
                };
                $.ajax({
                    data: data,
                    url: "{{ route('kategori.save') }}",
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
                        $('#btnSaveKategori').html('Simpan');
                        $('#modalAddKategori').modal('hide');
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
                        var url = "{{ route('kategori.delete', ['kategori' => ':id']) }}"
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
