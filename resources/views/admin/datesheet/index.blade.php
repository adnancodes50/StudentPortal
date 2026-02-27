@extends('adminlte::page')

@section('title', 'Date Sheet')

@section('content_header')
    <h1 class="m-0">Date Sheet</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if(($assignedCourses ?? collect())->isEmpty())
            <div class="alert alert-warning mb-3">
                No courses are assigned yet, so date sheet cannot be shown.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Exam Date Sheet</h3>
                </div>

                <div class="card-body p-0">
                    <div class="p-3 border-bottom bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-3 mb-2 mb-md-0 font-weight-bold">Select Term:</div>
                            <div class="col-md-9">
                                <select id="term-filter" class="form-control">
                                    <option value="">Select Term</option>
                                    <option value="1">Mid Term</option>
                                    <option value="2">Final Term</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="datesheet-hint" class="p-3 text-muted">
                        Select a term to load date sheet.
                    </div>

                    <div id="datesheet-table-wrap" class="table-responsive d-none">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Course</th>
                                    <th class="d-none d-md-table-cell">Paper</th>
                                    <th>Term</th>
                                    <th class="d-none d-md-table-cell">Type</th>
                                    <th class="d-none d-md-table-cell">Class</th>
                                    <th>Section</th>
                                    <th>Time</th>
                                    <th class="d-none d-lg-table-cell">Room</th>
                                </tr>
                            </thead>
                            <tbody id="datesheet-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var select = document.getElementById('term-filter');
            var hint = document.getElementById('datesheet-hint');
            var tableWrap = document.getElementById('datesheet-table-wrap');
            var tbody = document.getElementById('datesheet-body');
            var endpoint = @json(route('student.datesheet.data'));

            if (!select || !hint || !tableWrap || !tbody) {
                return;
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function setLoading() {
                hint.classList.remove('d-none');
                hint.textContent = 'Loading...';
                tableWrap.classList.add('d-none');
                tbody.innerHTML = '';
            }

            function setHint(message) {
                hint.classList.remove('d-none');
                hint.textContent = message;
                tableWrap.classList.add('d-none');
                tbody.innerHTML = '';
            }

            function renderRows(rows) {
                tbody.innerHTML = rows.map(function (row) {
                    return `
                        <tr>
                            <td>${escapeHtml(row.sr)}</td>
                            <td>${escapeHtml(row.date)}</td>
                            <td>${escapeHtml(row.course)}</td>
                            <td class="d-none d-md-table-cell">${escapeHtml(row.paper)}</td>
                            <td>${escapeHtml(row.term)}</td>
                            <td class="d-none d-md-table-cell">${escapeHtml(row.type)}</td>
                            <td class="d-none d-md-table-cell">${escapeHtml(row.class)}</td>
                            <td>${escapeHtml(row.section)}</td>
                            <td>${escapeHtml(row.time)}</td>
                            <td class="d-none d-lg-table-cell">${escapeHtml(row.room)}</td>
                        </tr>
                    `;
                }).join('');

                hint.classList.add('d-none');
                tableWrap.classList.remove('d-none');
            }

            select.addEventListener('change', function () {
                var term = select.value;

                if (!term) {
                    setHint('Select a term to load date sheet.');
                    return;
                }

                setLoading();

                fetch(endpoint + '?term=' + encodeURIComponent(term), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    var rows = Array.isArray(data.rows) ? data.rows : [];

                    if (rows.length === 0) {
                        setHint(data.message || 'No date sheet found for selected term.');
                        return;
                    }

                    renderRows(rows);
                })
                .catch(function () {
                    setHint('Failed to fetch date sheet. Please try again.');
                });
            });
        });
    </script>
@stop
