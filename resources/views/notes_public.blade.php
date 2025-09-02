<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Public Notes Index</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/public .css') }}" rel="stylesheet">
    <style>
        .note-card {
            cursor: pointer;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .note-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .note-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .note-card-link:hover {
            color: inherit;
            text-decoration: none;
        }
        
        .note-actions {
            position: relative;
            z-index: 10;
        }
        
        .btn-like {
            position: relative;
            z-index: 20;
        }
        
        /* Memastikan tombol like tetap bisa diklik */
        .btn-like:hover {
            transform: translateY(-2px) scale(1.1);
            color: #dc3545;
        }
        
        .btn-like:hover svg {
            transform: scale(1.2);
        }
    </style>

</head>
<body>


<aside class="sidebar shadow-sm">
    <div class="brand">NOTES</div>
    <nav class="nav flex-column gap-1">
        <a class="nav-link" href="{{ route('notes.index') }}">My Notes</a>
        <a class="nav-link active" href="{{ route('notes.public') }}">Public</a>
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
            <h1 class="page-title">Public Notes</h1>
        </div>
        <div class="search-container">
            <input type="search" class="form-control search-input" placeholder="Cari notes public..." aria-label="Search" id="searchNote" />
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

    
    <div class="row" id="noteList">
        @forelse($publicNotes as $note)
            <div class="col-12 col-sm-6 col-lg-3 mb-3 note-col">
                <a href="{{ route('notes.show', $note->id) }}" class="note-card-link">
                    <div class="note-card note-item">
                        <div class="note-title">{{ $note->judul }}</div>
                        <div class="note-content">{!! nl2br(e($note->deskripsi)) !!}</div>
                        <div class="note-date text-muted" style="font-size:12px;">
                            <span class="badge bg-success me-1">Public</span>
                            Author: {{ $note->user->name }}<br>
                            Terakhir diubah: {{ \Carbon\Carbon::parse($note->updated_at)->setTimezone('Asia/Makassar')->translatedFormat('d F Y H:i') }}
                        </div>
                        <div class="note-actions">
                            <button class="btn-like" data-note-id="{{ $note->id }}" title="Like Notes">
                                <svg class="bi bi-heart" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                                <span class="likes-count">{{ $note->likes_count }}</span>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">Belum ada notes public.</p>
            </div>
        @endforelse
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        title: "Good job!",
        text: "{{ session('success') }}",
        icon: "success"
    });
});
</script>
@endif

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


    const likeButtons = document.querySelectorAll('.btn-like');
    
    likeButtons.forEach(button => {
        const noteId = button.dataset.noteId;
        const heartIcon = button.querySelector('svg');
        const likesCount = button.querySelector('.likes-count');
        
        // Check initial like status
        fetch(`/notes/${noteId}/like/check`)
            .then(response => response.json())
            .then(data => {
                if (data.is_liked) {
                    heartIcon.style.fill = '#dc3545';
                    button.classList.add('liked');
                } else {
                    heartIcon.style.fill = '#6c757d';
                    button.classList.remove('liked');
                }
                likesCount.textContent = data.likes_count;
            });
        
        // Handle like button click
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            fetch(`/notes/${noteId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.is_liked) {
                        heartIcon.style.fill = '#dc3545';
                        button.classList.add('liked');
                    } else {
                        heartIcon.style.fill = '#6c757d';
                        button.classList.remove('liked');
                    }
                    likesCount.textContent = data.likes_count;
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>

</body>
</html>
