<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Catatan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/public .css') }}" rel="stylesheet">
    <style>
        /* CSS khusus untuk header tanpa search bar */
        .page-header .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }
        
        .page-header .header-left {
            position: static;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .page-header .search-container {
            min-width: 300px;
            visibility: hidden;
        }
        
        /* Responsive untuk header tanpa search */
        @media (max-width: 768px) {
            .page-header .header-content {
                flex-direction: column;
                gap: 15px;
                position: static;
            }
            
            .page-header .header-left {
                position: static;
                order: 1;
            }
            
            .page-header .search-container {
                min-width: 250px;
                order: 2;
            }
        }
    </style>
</head>
<body>

<aside class="sidebar shadow-sm">
    <div class="brand">NOTES</div>
    <nav class="nav flex-column gap-1">
        <a class="nav-link" href="{{ route('notes.index') }}">My Notes</a>
        <a class="nav-link" href="{{ route('notes.public') }}">Public</a>
    </nav>
    <div class="footer">
        <form action="{{ route('logout') }}" method="POST" class="d-grid">
            @csrf
            <button type="submit" class="btn btn-logout" title="Logout" aria-label="Logout">
                <svg class="bi bi-box-arrow-right" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<header class="page-header">
    <div class="header-content">
        <div class="header-left">
            <h1 class="page-title">Detail Note</h1>
        </div>
        <div class="search-container">
            <!-- Search container kosong untuk menjaga layout -->
        </div>
    </div>
</header>

<div class="content-wrapper">
    
    <div class="user-profile">
        <div class="profile-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="profile-info">
            <h6>Halo, {{ $user->name }}!</h6>
            <p>{{ $user->email }}</p>
        </div>
    </div>

    <div class="container-custom">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="note-card">
                    <h1 class="mb-4">{{ $note->judul }}</h1>

                    <div class="note-content mb-4">
                        {!! nl2br(e($note->deskripsi)) !!}
                    </div>

                    <div class="note-date text-muted mb-4">
                        @if($note->is_public)
                            <span class="badge bg-success me-1">Public</span>
                        @else
                            <span class="badge bg-secondary me-1">Private</span>
                        @endif
                        <strong>Dibuat oleh:</strong> {{ $note->user->name }}<br>
                        <strong>Dibuat pada:</strong> {{ \Carbon\Carbon::parse($note->created_at)->setTimezone('Asia/Makassar')->translatedFormat('d F Y H:i') }}<br>
                        <strong>Terakhir diubah:</strong> {{ \Carbon\Carbon::parse($note->updated_at)->setTimezone('Asia/Makassar')->translatedFormat('d F Y H:i') }}
                    </div>

                    <div class="d-flex gap-2">
                        @if($note->is_public)
                            <a href="{{ route('notes.public') }}" class="btn btn-secondary">Kembali ke Public Notes</a>
                        @else
                            <a href="{{ route('notes.index') }}" class="btn btn-secondary">Kembali ke My Notes</a>
                        @endif
                        @if(auth()->id() == $note->user_id)
                            <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-primary">Edit</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const logoutForm = document.querySelector("form[action*='logout']");
    if (logoutForm) {
        logoutForm.addEventListener("submit", function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Logout",
                text: "Apakah kamu yakin ingin keluar dari akun?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#14b8a6",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, logout!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    logoutForm.submit();
                }
            });
        });
    }


});
</script>

</body>
</html>
