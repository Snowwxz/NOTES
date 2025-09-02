<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Catatan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Custom CSS --}}
  <link href="{{ asset('css/create.css') }}" rel="stylesheet">
</head>
<body>

  {{-- HEADER/TOPBAR --}}
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm rounded-bottom" style="border-radius: 0 0 16px 16px; box-shadow: 0 2px 8px rgba(72, 58, 160, 0.15);">
    <div class="container-fluid">
       <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="{{ route('notes.index') }}">
            </svg>
            NOTES
        </a>
        
        {{-- Navbar Notes dan Public --}}
        <div class="navbar-nav me-auto">
            <a class="nav-link" href="{{ route('notes.index') }}">My Notes</a>
            <a class="nav-link" href="{{ route('notes.public') }}">Public</a>
        </div>
        
        <div class="ms-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm" title="Logout">
                    <svg class="bi bi-box-arrow-right" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
  </nav>

  {{-- KONTEN --}}
  <div class="container-custom create">
    <h1 class="mb-4">Tambah Catatan</h1>

    {{-- Pesan error --}}
    @if ($errors->any())
      <div class="error-box">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('notes.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" id="judul" name="judul"
          class="form-control"
          value="{{ old('judul') }}"
          placeholder="Masukkan judul catatan" required>
      </div>

      <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="6"
          class="form-control"
          placeholder="Tulis deskripsi catatan" required>{{ old('deskripsi') }}</textarea>
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_public" name="is_public" {{ old('is_public') ? 'checked' : '' }}>
          <label class="form-check-label" for="is_public">
            <strong>Jadikan catatan ini public</strong> <small class="text-muted">(Catatan public akan terlihat oleh semua user)</small>
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-success">Tambah</button>
      <a href="{{ route('notes.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>

  {{-- Bootstrap JS + SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
      // Konfirmasi logout
      const logoutForm = document.querySelector("form[action*='logout']");
      if (logoutForm) {
          logoutForm.addEventListener("submit", function (e) {
              e.preventDefault();
              Swal.fire({
                  title: "Logout",
                  text: "Apakah kamu yakin ingin keluar dari akun?",
                  icon: "question",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
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
