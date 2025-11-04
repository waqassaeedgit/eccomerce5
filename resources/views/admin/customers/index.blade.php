@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers Management')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name or email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Total Orders</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $customer->orders_count }}</span>
                                </td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No customers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection