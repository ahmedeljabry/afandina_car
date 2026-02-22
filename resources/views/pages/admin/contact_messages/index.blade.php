@extends('layouts.admin_layout')

@section('title', 'Contact Messages')

@section('page-title')
    Contact Messages
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-envelope-open-text mr-2"></i>Contact Messages
            </h3>
            <span class="badge badge-primary">{{ $messages->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Sent At</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $message)
                            <tr>
                                <td>{{ $message->id }}</td>
                                <td>{{ $message->full_name }}</td>
                                <td>{{ $message->email ?: '-' }}</td>
                                <td>{{ $message->phone ?: '-' }}</td>
                                <td style="min-width: 260px;">{{ \Illuminate\Support\Str::limit($message->message, 160) }}</td>
                                <td>{{ $message->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-right">
                                    <form action="{{ route('admin.contact-messages.destroy', $message->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this message?');"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No contact messages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($messages->hasPages())
            <div class="card-footer">
                {{ $messages->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
