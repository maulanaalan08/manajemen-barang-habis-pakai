<!-- jQuery & jQuery UI (Dimuat pertama agar selalu tersedia) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('template/dist/assets/js/bootstrap.js') }}"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Mazer Template Core JS -->
<script src="{{ asset('template/dist/assets/js/app.js') }}"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable aman untuk #table1
        const $table = $('#table1');
        if ($table.length && !$.fn.DataTable.isDataTable('#table1')) {
            const thCount = $table.find('thead th').length;
            if (thCount > 0) {
                // Hapus baris colspan manual jika ada pesan kosong agar DataTables tidak crash
                const $emptyColspanRow = $table.find('tbody tr td[colspan]');
                if ($emptyColspanRow.length === 1 && $table.find('tbody tr').length === 1) {
                    $table.find('tbody').empty();
                }

                $table.DataTable({
                    destroy: true,
                    retrieve: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Data tidak ditemukan",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });
            }
        }
    });
</script>
