<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - {{ $asalInstansi }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Klien dari {{ $asalInstansi }}</h1>

        <!-- Upload Form -->
        <h3>Upload Data Klien (Excel)</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('users.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="asalInstansi" value="{{ $asalInstansi }}">
            <div class="form-group">
                <label for="excelFile">Pilih Excel</label>
                <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx,.xls" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Upload</button> <!-- Full-width button -->
        </form>

        <div class="text-center mb-3 mt-3">
            <a href="{{ route('users.export', $asalInstansi) }}" class="btn btn-success">Download Excel</a>
        </div>

        <div class="table-responsive"> <!-- Make table responsive -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Selected Group</th>
                        <th>Agility</th>
                        <th>Leadership</th>
                        <th>Team Work</th>
                        <th>Personality Result</th> <!-- New column for personality result -->
                    </tr>
                </thead>
                <tbody>
                    @if($usersFromInstansi && count($usersFromInstansi) > 0)
                        @foreach ($usersFromInstansi as $user)
                            <tr>
                                <td>{{ $user['nama'] ?? 'N/A' }}</td>
                                <td>{{ $user['selectedGroup'] ?? 'N/A' }}</td>
                                <td>{{ $user['ratings']['agility'] ?? 'N/A' }}</td>
                                <td>{{ $user['ratings']['leadership'] ?? 'N/A' }}</td>
                                <td>{{ $user['ratings']['teamWork'] ?? 'N/A' }}</td>
                                <td>{{ $user['personalityResult']['result'] ?? 'N/A' }}</td> <!-- Display personality result -->
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">Tidak ada User</td> <!-- Adjusted colspan for the new column -->
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="{{ route('firebase.index') }}" class="btn btn-primary">Back to Users</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
