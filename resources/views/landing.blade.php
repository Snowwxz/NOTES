<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SamaBaca - Public Notes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/public .css') }}" rel="stylesheet">
 
</head>
<body>


<aside class="sidebar shadow-sm">
    <div class="brand">NOTES</div>
    <nav class="nav flex-column gap-1">
        <a class="nav-link active" href="{{ route('landing') }}">Public Notes</a>
    </nav>
    <div class="footer">
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm w-100">Sign In</a>
        </div>
    </div>
</aside>


<header class="page-header">
    <div class="header-content">
        <div class="header-left">
            <h1 class="page-title">Public Notes</h1>
        </div>
        <div class="search-container">
            <input type="search" class="form-control search-input" placeholder="Cari notes public..." aria-label="Search" id="searchNote" />
        </div>

    </div>
</header>


<div class="content-wrapper">
    <div class="container-custom">
        
        <div class="row" id="noteList">
            @forelse($publicNotes as $note)
                <div class="col-12 col-sm-6 col-lg-3 mb-3 note-col">
                    <div class="note-card note-item">
                        <div class="note-title">{{ $note->judul }}</div>
                        <div class="note-content">{!! nl2br(e($note->deskripsi)) !!}</div>
                        <div class="note-date text-muted" style="font-size:12px;">
                            <span class="badge bg-success me-1">Public</span>
                            Author: {{ $note->user->name }}<br>
                            Terakhir diubah: {{ \Carbon\Carbon::parse($note->updated_at)->setTimezone('Asia/Makassar')->translatedFormat('d F Y H:i') }}
                        </div>
                        <div class="note-actions">
                            <button class="btn-like like-disabled" data-note-id="{{ $note->id }}" title="Login untuk like notes" onclick="redirectToLogin()">
                                <svg class="bi bi-heart" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                                <span class="likes-count">{{ $note->likes_count }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <h4 class="text-muted mb-3">Belum ada notes public</h4>
                        <p class="text-muted">Jadilah yang pertama untuk berbagi notes menarik!</p>
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById('searchNote');
    const noteCols = document.querySelectorAll('.note-col');

    function filterNotes(keyword) {
        const lowerKeyword = keyword.toLowerCase();
        let found = false;

        noteCols.forEach(col => {
            const title = (col.querySelector('.note-title')?.textContent || '').toLowerCase();
            const content = (col.querySelector('.note-content')?.textContent || '').toLowerCase();

            if (title.includes(lowerKeyword) || content.includes(lowerKeyword)) {
                col.style.display = '';
                found = true;
            } else {
                col.style.display = 'none';
            }
        });


        if (!found) {
            if (!document.getElementById('noResult')) {
                const noResult = document.createElement('div');
                noResult.id = 'noResult';
                noResult.className = 'col-12 text-center text-muted';
                noResult.innerHTML = '<p>Notes public tidak ditemukan.</p>';
                document.getElementById('noteList').appendChild(noResult);
            }
        } else {
            const noResult = document.getElementById('noResult');
            if (noResult) noResult.remove();
        }
    }

    searchInput.addEventListener('input', function () {
        filterNotes(this.value.trim());
    });
});


function redirectToLogin() {
    Swal.fire({
        title: "Login Diperlukan",
        text: "Silakan login terlebih dahulu untuk like notes",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#14b8a6",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Login Sekarang",
        cancelButtonText: "Nanti"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('login') }}";
        }
    });
}
</script>

</body>
</html>
