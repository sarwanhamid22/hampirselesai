@extends('layouts.master')

@section('title', 'Students List')

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
                            <li class="breadcrumb-item active">Kelola Profil Siswa</li>
                        </ol>
                    </div>
                    @role('admin')
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="{{ route('createStudents') }}" class="btn btn-primary">Tambah Siswa</a>
                    </div>
                    @endrole
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        @if ($students->isEmpty())
                            <div class="alert alert-warning">
                                No students found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Student ID</th>
                                            <th>Class</th>
                                            <th>Birth Date</th>
                                            <th>Address</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->id }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->student_id }}</td>
                                                <td>{{ $student->class->name ?? 'No Class' }}</td>
                                                <td>{{ $student->birth_date->format('d-m-Y') }}</td>
                                                <td>{{ $student->address }}</td>
                                                <td>{{ $student->phone_number }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="{{ route('showStudents', $student->id) }}"
                                                            class="btn btn-info btn-sm mr-2 rounded" title="View"><i
                                                                class="fas fa-eye"></i></a>
                                                                @role('admin')
                                                        <a href="{{ route('editStudents', $student->id) }}"
                                                            class="btn btn-warning btn-sm mr-2 rounded" title="Edit"><i
                                                                class="fas fa-edit"></i></a>
                                                        <button type="button" class="btn btn-danger btn-sm rounded"
                                                            onclick="confirmDelete('{{ route('deleteStudents', ['student' => $student->id]) }}')"
                                                            title="Delete"><i class="fas fa-trash-alt"></i></button>
                                                            @endrole
                                                    </div>
                                                    <form id="delete-form-{{ $student->id }}"
                                                        action="{{ route('deleteStudents', ['student' => $student->id]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
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
                    title: 'Confirm Delete',
                    text: 'Are you sure you want to delete this data?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(function(value) {
                    if (value) {
                        $('#delete-form-' + url.split('/')[url.split('/').length - 1]).submit();
                    }
                });
            }
        </script>
    @endsection
