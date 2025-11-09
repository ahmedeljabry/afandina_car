@extends('layouts.admin_layout')

@section('title', 'Mange Sitemap')

@section('page-title')
    <Man></Man>
@endsection

@section('content')
<div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Translation Management</h3>
                            <div>
                                <button id="generateSitemap" class="btn btn-primary">Generate Sitemap</button>
                                <a href="{{ route('admin.sitemap.download') }}" class="btn btn-success">Download Sitemap</a>
                                <button id="notifySearchEngines" class="btn btn-warning">Notify Google & Bing</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.getElementById('generateSitemap').addEventListener('click', function() {
            fetch("{{ route('admin.sitemap.generate') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            }).then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => alert("Error generating sitemap!"));
        });

        document.getElementById('notifySearchEngines').addEventListener('click', function() {
            fetch("{{ route('admin.sitemap.notify') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            }).then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => alert("Error notifying search engines!"));
        });
    </script>
@endsection
