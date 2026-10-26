<!DOCTYPE html>
<html lang="en">

<body>

    @include('component-web.sidebar')
    @include('component-web.head')


    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <h3>Master Barang</h3>
        </div>

        <section class="section">
            <form id="permissionForm" method="POST" action="{{ route('roles.update-permissions') }}">
                @csrf
                <div class="card">
                    <h1>Hak Akses</h1>

                    <!-- PILIH ROLE -->
                    <div class="card-body">
                        <label for="roleSelect" class="form-label">Pilih Role</label>
                        <select class="form-select" id="roleSelect" name="role_id" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- DAFTAR PERMISSION -->
                    <div class="card-body">
                        <h3>Daftar Permission</h3>
                        <div id="permissionsContainer">
                            @foreach ($permissions as $permission)
                            <label>
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" disabled>
                                {{ $permission->name }}
                            </label><br>
                            @endforeach
                        </div>
                    </div>

                    <!-- TOMBOL SIMPAN -->
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Simpan Perubahan</button>
                    </div>
                </div>
            </form>

        </section>

        @include('component-web.footer')

    </div>

    @include('component-web.script')
    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const roleId = this.value;
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            const submitBtn = document.getElementById('submitBtn');

            // Reset semua checkbox
            checkboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
            });

            submitBtn.disabled = true;

            if (roleId) {
                fetch(`/get-permissions-by-role/${roleId}`)
                    .then(response => response.json())
                    .then(data => {
                        checkboxes.forEach(cb => {
                            cb.disabled = false;
                            if (data.permission_names.includes(cb.value)) {
                                cb.checked = true;
                            }
                        });

                        // Enable tombol simpan
                        submitBtn.disabled = false;
                    });

                // Set hidden input for role_id jika tidak pakai "name" di select
                document.querySelector('input[name="role_id"]')?.remove();
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'role_id';
                hidden.value = roleId;
                document.getElementById('permissionForm').appendChild(hidden);
            }
        });
    </script>





</body>

</html>