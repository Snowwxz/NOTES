<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>My Notes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/notes.css') }}" rel="stylesheet">

</head>
<body>


<aside class="sidebar shadow-sm">
    <div class="brand">NOTES</div>
    <nav class="nav flex-column gap-1">
        <a class="nav-link active" href="{{ route('notes.index') }}">My Notes</a>
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
            <h1 class="page-title">My Notes</h1>
        </div>
        <div class="search-container">
            <input type="search" class="form-control search-input" placeholder="Cari notes saya..." aria-label="Search" id="searchNote" />
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
        @forelse($notes as $note)
            <div class="col-12 col-sm-6 col-lg-3 mb-3 note-col">
                <div class="note-card note-item">
                    <div class="note-title">{{ $note->judul }}</div>
                    <div class="note-content">{!! nl2br(e($note->deskripsi)) !!}</div>
                    <div class="note-date text-muted" style="font-size:12px;">
                        @if($note->is_public)
                            <span class="badge bg-success me-1">Public</span>
                        @else
                            <span class="badge bg-secondary me-1">Private</span>
                        @endif
                        @if($note->user_id !== $user->id)
                            <span class="badge bg-info me-1">Shared</span>
                            <div class="mt-1">
                                <small class="text-muted">
                                    <svg class="bi bi-person" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                    </svg>
                                    Shared by: {{ $note->user->name }}
                                </small>
                            </div>
                        @endif
                        Terakhir diubah: {{ \Carbon\Carbon::parse($note->updated_at)->setTimezone('Asia/Makassar')->translatedFormat('d F Y H:i') }}
                    </div>
                    <div class="note-actions">
                        <button class="btn-edit" title="Edit Notes"
                                data-note-id="{{ $note->id }}"
                                data-note-judul="{{ $note->judul }}"
                                data-note-deskripsi="{{ $note->deskripsi }}"
                                data-note-public="{{ $note->is_public ? 'true' : 'false' }}"
                                data-note-owner="{{ $note->user->name }}"
                                data-note-owner-email="{{ $note->user->email }}"
                                data-note-owner-id="{{ $note->user->id }}"
                                onclick="openEditModalFromData(this)">
                            <svg class="bi bi-pencil" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                        </button>
                        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="form-delete d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Hapus Notes">
                                <svg class="bi bi-trash" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">Belum ada notes.</p>
            </div>
        @endforelse
    </div>
</div>


<button class="fab" data-bs-toggle="modal" data-bs-target="#addNoteModal">+</button>


<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="addNoteForm">
          @csrf
          <input type="text" name="judul" class="form-control mb-3" placeholder="Judul" required>
          <textarea name="deskripsi" class="form-control mb-3" rows="4" placeholder="Deskripsi" required></textarea>
          <div class="form-check mb-3">
            <input type="checkbox" name="is_public" value="1" class="form-check-input" id="isPublic">
            <label class="form-check-label" for="isPublic">Public</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header">
        <h5 class="modal-title">Edit Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editNoteForm">
          @csrf
          @method('PUT')
          <input type="hidden" name="note_id" id="editNoteId">
          <input type="text" name="judul" id="editJudul" class="form-control mb-3" placeholder="Judul" required>
          <textarea name="deskripsi" id="editDeskripsi" class="form-control mb-3" rows="4" placeholder="Deskripsi" required></textarea>

          
          <div class="collaboration-section mb-3" id="collaborationSection" style="display: none;">
            <h6 class="fw-bold mb-2">Kolaborator</h6>
            <div class="input-group mb-2">
              <select id="collaboratorSelect" class="form-select">
                <option value="">Pilih user untuk di-invite...</option>
              </select>
              <button type="button" class="btn btn-outline-primary" id="addCollaboratorBtn">
                <svg class="bi bi-plus" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Invite
              </button>
            </div>
            <div id="collaboratorsList" class="mb-2">
              <!-- Collaborators will be loaded here -->
            </div>
          </div>

          <div class="form-check mb-3">
            <input type="checkbox" name="is_public" value="1" class="form-check-input" id="editIsPublic">
            <label class="form-check-label" for="editIsPublic">Public</label>
          </div>
          <button type="submit" class="btn btn-primary w-100">Update</button>
        </form>
      </div>
    </div>
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

function openEditModalFromData(button) {
    const noteId = button.getAttribute('data-note-id');
    const judul = button.getAttribute('data-note-judul');
    const deskripsi = button.getAttribute('data-note-deskripsi');
    const isPublic = button.getAttribute('data-note-public') === 'true';
    const ownerName = button.getAttribute('data-note-owner');
    const ownerId = button.getAttribute('data-note-owner-id');
    const currentUserId = {{ $user->id }};

    document.getElementById('editNoteId').value = noteId;
    document.getElementById('editJudul').value = judul;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editIsPublic').checked = isPublic;


    const collaborationSection = document.getElementById('collaborationSection');
    if (collaborationSection) {
        collaborationSection.style.display = 'block';
        console.log('Collaboration section displayed for user:', currentUserId, 'owner:', ownerId);
        
        loadCollaborators(noteId, currentUserId, ownerId);
    } else {
        console.error('Collaboration section element not found!');
    }


    const editModal = new bootstrap.Modal(document.getElementById('editNoteModal'));
    editModal.show();
}


function loadCollaborators(noteId, currentUserId, ownerId) {
    fetch(`/collab/list?note_id=${noteId}`, {
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            displayCollaborators(data.collaborators, noteId, currentUserId, ownerId);
        }
    })
    .catch(err => {
        console.error('Error loading collaborators:', err);
    });


    console.log('About to load available users for note:', noteId);
    loadAvailableUsers(noteId);
}


function loadAvailableUsers(noteId) {
    console.log('Loading available users for note:', noteId);
    fetch(`/collab/available-users?note_id=${noteId}`, {
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(data => {
        console.log('Available users response:', data);
        if (data.success) {
            populateUserDropdown(data.users);
        } else {
            console.error('Failed to load available users:', data.message);
            
            const select = document.getElementById('collaboratorSelect');
            select.innerHTML = '<option value="">Error: ' + data.message + '</option>';
        }
    })
    .catch(err => {
        console.error('Error loading available users:', err);
        
        const select = document.getElementById('collaboratorSelect');
        select.innerHTML = '<option value="">Error loading users</option>';
    });
}


function populateUserDropdown(users) {
    console.log('Populating dropdown with users:', users);
    const select = document.getElementById('collaboratorSelect');
    const addBtn = document.getElementById('addCollaboratorBtn');

    if (!select) {
        console.error('Collaborator select element not found!');
        return;
    }

    select.innerHTML = '<option value="">Pilih user untuk di-invite...</option>';

    if (!users || users.length === 0) {
        select.innerHTML = '<option value="">Tidak ada user yang tersedia</option>';
        addBtn.disabled = true;
        addBtn.innerHTML = `
            <svg class="bi bi-plus" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            Invite
        `;
        return;
    }

    users.forEach(user => {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = `${user.name} (${user.email})`;
        select.appendChild(option);
    });

    addBtn.disabled = false;
    addBtn.innerHTML = `
        <svg class="bi bi-plus" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Invite
    `;
}


function displayCollaborators(collaborators, noteId, currentUserId, ownerId) {
    const collaboratorsList = document.getElementById('collaboratorsList');
    collaboratorsList.innerHTML = '';


    const editButton = document.querySelector(`[data-note-id="${noteId}"]`);
    const ownerName = editButton.getAttribute('data-note-owner');


    const ownerItem = document.createElement('div');
    ownerItem.className = 'd-flex align-items-center p-2 border rounded mb-1 owner-item';
    ownerItem.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="owner-avatar me-2">
                ${ownerName.charAt(0).toUpperCase()}
            </div>
            <div>
                <strong>${ownerName}</strong> <span class="badge bg-success ms-1">Pemilik</span>
                <br>
                <small class="text-muted">${editButton.getAttribute('data-note-owner-email') || 'email@example.com'}</small>
            </div>
        </div>
    `;
    collaboratorsList.appendChild(ownerItem);


    if (collaborators.length === 0) {
        const noCollaborators = document.createElement('div');
        noCollaborators.className = 'p-2 text-muted small';
        noCollaborators.innerHTML = '<em>Tidak ada kolaborator tambahan</em>';
        collaboratorsList.appendChild(noCollaborators);
        return;
    }

    collaborators.forEach(collaborator => {
        const collaboratorItem = document.createElement('div');
        collaboratorItem.className = 'd-flex align-items-center justify-content-between p-2 border rounded mb-1 collaborator-item';



        const removeButton = (currentUserId == ownerId || (currentUserId != ownerId && currentUserId != collaborator.id)) ? `
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCollaborator(${noteId}, ${collaborator.id})">
                <svg class="bi bi-x" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        ` : '';

        collaboratorItem.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="collaborator-avatar me-2">
                    ${collaborator.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <strong>${collaborator.name}</strong>
                    <br>
                    <small class="text-muted">${collaborator.email}</small>
                </div>
            </div>
            ${removeButton}
        `;
        collaboratorsList.appendChild(collaboratorItem);
    });
}


function removeCollaborator(noteId, userId) {
    Swal.fire({
        title: "Hapus Kolaborator",
        text: "Apakah Anda yakin ingin menghapus kolaborator ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#14b8a6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('note_id', noteId);
            formData.append('user_id', userId);

            fetch('/collab/remove', {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Berhasil!", data.message, "success");

                    const editButton = document.querySelector(`[data-note-id="${noteId}"]`);
                    const currentUserId = {{ $user->id }};
                    const ownerId = editButton.getAttribute('data-note-owner-id');
                    loadCollaborators(noteId, currentUserId, ownerId);

                    loadAvailableUsers(noteId);
                } else {
                    Swal.fire("Error!", data.message, "error");
                }
            })
            .catch(err => {
                console.error('Error:', err);
                Swal.fire("Error!", "Terjadi kesalahan saat menghapus kolaborator", "error");
            });
        }
    });
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const deleteForms = document.querySelectorAll(".form-delete");
    deleteForms.forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Apakah kamu yakin?",
                text: "Notes yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#14b8a6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus saja!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });


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
                noResult.innerHTML = '<p>Notes tidak ditemukan.</p>';
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


    const addNoteForm = document.getElementById('addNoteForm');
    addNoteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("{{ route('notes.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            addNoteForm.reset();
            bootstrap.Modal.getInstance(document.getElementById('addNoteModal')).hide();

            Swal.fire({
                title: "Berhasil!",
                text: data.message,
                icon: "success"
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                title: "Error!",
                text: 'Terjadi kesalahan saat menyimpan notes',
                icon: "error"
            });
        });
    });


    const editNoteForm = document.getElementById('editNoteForm');
    editNoteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const noteId = document.getElementById('editNoteId').value;

        fetch(`/notes/${noteId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest",
                "X-HTTP-Method-Override": "PUT"
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            editNoteForm.reset();
            bootstrap.Modal.getInstance(document.getElementById('editNoteModal')).hide();

            Swal.fire({
                title: "Berhasil!",
                text: data.message,
                icon: "success"
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                title: "Error!",
                text: 'Terjadi kesalahan saat mengupdate notes',
                icon: "error"
            });
        });
    });


    const addCollaboratorBtn = document.getElementById('addCollaboratorBtn');
    const collaboratorSelect = document.getElementById('collaboratorSelect');

    addCollaboratorBtn.addEventListener('click', function() {
        const userId = collaboratorSelect.value;
        const noteId = document.getElementById('editNoteId').value;

        if (!userId) {
            Swal.fire("Error!", "Pilih user untuk di-invite", "error");
            return;
        }

        const formData = new FormData();
        formData.append('note_id', noteId);
        formData.append('user_id', userId);

        fetch('/collab/add', {
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire("Berhasil!", data.message, "success");
                collaboratorSelect.value = '';
                loadCollaborators(noteId);

                loadAvailableUsers(noteId);
            } else {
                Swal.fire("Error!", data.message, "error");
            }
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire("Error!", "Terjadi kesalahan saat menambahkan kolaborator", "error");
        });
    });
});
</script>

</body>
</html>
