<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Activity Logs</h4>
    </div>

    {{-- Filters --}}
    <div class="panel bg-white shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" placeholder="Search description..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="module">
                    <option value="">All Modules</option>
                    @foreach ($modules as $m)
                        <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="action">
                    <option value="">All Actions</option>
                    @foreach ($actions as $a)
                        <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-1">
                <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel bg-white shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>User</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $log)
                        <tr>
                            <td style="color: #1a1a2e;">{{ $log->user->name ?? 'System' }}</td>
                            <td>
                                @if ($log->module)
                                    <span class="badge badge-soft-secondary rounded-pill">{{ $log->module }}</span>
                                @else
                                    <span class="badge badge-soft-secondary rounded-pill">—</span>
                                @endif
                            </td>
                            <td>
                                @php $action = $log->action; @endphp
                                @if ($action == 'created' || $action == 'activated')
                                    <span class="badge bg-success rounded-pill">{{ ucfirst($action) }}</span>
                                @elseif ($action == 'updated')
                                    <span class="badge bg-info text-white rounded-pill">{{ ucfirst($action) }}</span>
                                @elseif ($action == 'deleted' || $action == 'deactivated')
                                    <span class="badge bg-danger rounded-pill">{{ ucfirst($action) }}</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($action) }}</span>
                                @endif
                            </td>
                            <td style="color: #5a6270;">{{ $log->description }}</td>
                            <td style="color: #a4b0c2;">
                                {{ $log->created_at->format('M d, Y h:i A') }}
                                <br><small>{{ $log->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $activities->links() }}
        </div>
    </div>
</x-app-layout>
