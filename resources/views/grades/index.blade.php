@extends('layouts.master')

@section('title', 'Grades')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 justify-content-between align-items-center">
                    <div class="col-md-6 d-flex align-items-center">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Nilai</li>
                        </ol>
                    </div>
                    @hasanyrole('admin|teacher')
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="{{ route('createGrades') }}" class="btn btn-primary">Tambah Nilai</a>
                    </div>
                    @endhasanyrole
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Nomor Induk Siswa</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grades as $grade)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $grade->student->name }}</td>
                                            <td>{{ $grade->student->student_id }}</td>
                                            <td>{{ optional($grade->student->class)->name }}</td>
                                            <td>{{ $grade->subject->name_subjects }}</td>
                                            <td>{{ $grade->grade }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{ route('showGrades', $grade) }}"
                                                        class="btn btn-info btn-sm mr-2 rounded" title="Lihat"><i
                                                            class="fas fa-eye"></i></a>
                                                            @hasanyrole('admin|teacher')
                                                    <a href="{{ route('editGrades', $grade) }}"
                                                        class="btn btn-warning btn-sm mr-2 rounded" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-danger btn-sm rounded"
                                                        onclick="confirmDelete('{{ route('deleteGrades', ['grade' => $grade->id]) }}')"
                                                        title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                                        @endhasanyrole
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addJavascript')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        $(function() {
            $('#data-table').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Data tidak ditemukan",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'></i>",
                        next: "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });
        });

        function confirmDelete(url) {
            swal({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Kamu Yakin Ingin Menghapus Data Ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(function(value) {
                if (value) {
                    var form = document.createElement("form");
                    form.setAttribute("method", "POST");
                    form.setAttribute("action", url);
                    form.style.display = "none";
                    var csrfField = document.createElement("input");
                    csrfField.setAttribute("type", "hidden");
                    csrfField.setAttribute("name", "_token");
                    csrfField.setAttribute("value", "{{ csrf_token() }}");
                    form.appendChild(csrfField);
                    var methodField = document.createElement("input");
                    methodField.setAttribute("type", "hidden");
                    methodField.setAttribute("name", "_method");
                    methodField.setAttribute("value", "DELETE");
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
