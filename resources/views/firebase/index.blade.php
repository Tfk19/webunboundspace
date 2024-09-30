<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">List Instansi</h1>
        <div class="list-group">
            @if($uniqueInstitutions && count($uniqueInstitutions) > 0)
                @foreach ($uniqueInstitutions as $instansi)
                    <div class="list-group-item">
                        <a href="{{ route('firebase.userDetails', ['asalInstansi' => $instansi]) }}" class="btn btn-primary btn-block">
                            {{ $instansi }}
                        </a>
                    </div>
                @endforeach
            @else
                <div class="list-group-item text-center">Tidak Ada Instansi</div>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
