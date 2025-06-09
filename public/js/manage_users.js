// public/js/manage_users.js

$(document).ready(function() {
    const baseUrl = (typeof window.baseUrl !== 'undefined') ? window.baseUrl : 'http://localhost:8080/';

    // Ambil CSRF Token dan Name dari meta tag
    const csrfName = $('meta[name="csrf-name"]').attr('content') || '';
    let csrfHash = $('meta[name="csrf-token"]').attr('content') || '';

    // Perbarui CSRF Token di setiap request sukses
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.type === 'POST' || settings.type === 'post') {
            const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
            if (newToken && newToken !== csrfHash) {
                csrfHash = newToken;
                $('meta[name="csrf-token"]').attr('content', newToken); // Update meta tag di DOM
            } else {
                const currentMetaToken = $('meta[name="csrf-token"]').attr('content');
                if (currentMetaToken && currentMetaToken !== csrfHash) {
                    csrfHash = currentMetaToken;
                }
            }
        }
    });

    // Handle perubahan dropdown role
    $('.role-select').on('change', function() {
        const userId = $(this).data('user-id');
        const newRole = $(this).val();
        const userName = $(`#user-item-${userId} td[data-label="Nama"]`).text();

        // Simpan role saat ini untuk kemungkinan rollback jika konfirmasi dibatalkan
        const currentRole = $(this).data('current-role'); // Ambil dari data attribute, bukan dari .val()
        $(this).data('current-role', newRole); // Update data-attribute for next change

        Swal.fire({
            title: 'Konfirmasi Perubahan Role',
            text: `Anda yakin ingin mengubah role ${userName} menjadi ${newRole}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00796b',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'Ya, Ubah Role!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = {};
                formData['role'] = newRole;
                
                if (csrfName && csrfHash) {
                    formData[csrfName] = csrfHash;
                }

                const targetUrl = baseUrl + 'admin/users/edit-role/' + userId;
                console.log("Sending AJAX POST to:", targetUrl, "Data:", formData);

                $.ajax({
                    url: targetUrl,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            );
                            // Update data-current-role di dropdown setelah sukses
                            $(`.role-select[data-user-id="${userId}"]`).data('current-role', newRole);
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                            // Rollback dropdown jika gagal
                            $(`.role-select[data-user-id="${userId}"]`).val(currentRole);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan server: ' + xhr.status + ' ' + xhr.statusText;
                        if (xhr.responseText) {
                            try {
                                const jsonResponse = JSON.parse(xhr.responseText);
                                if (jsonResponse.message) {
                                    errorMessage += '. Detail: ' + jsonResponse.message;
                                }
                            } catch (e) {
                                errorMessage += '. Response: ' + xhr.responseText.substring(0, 100) + '...';
                            }
                        }
                        Swal.fire(
                            'Error!',
                            errorMessage,
                            'error'
                        );
                        console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
                        // Rollback dropdown jika ada error AJAX
                        $(`.role-select[data-user-id="${userId}"]`).val(currentRole);
                    }
                });
            } else {
                // Jika user membatalkan, kembalikan pilihan dropdown ke nilai semula
                $(this).val(currentRole);
            }
        });
    });

    // Handle klik tombol hapus user
    $('.btn-delete').on('click', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: `Anda yakin ingin menghapus user ${userName} (ID: ${userId})? Aksi ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, kirim form POST untuk penghapusan (full page refresh)
                const deleteForm = $(`<form action="${baseUrl}admin/users/delete/${userId}" method="post"></form>`);
                if (csrfName && csrfHash) {
                    deleteForm.append(`<input type="hidden" name="${csrfName}" value="${csrfHash}">`);
                }
                $('body').append(deleteForm);
                deleteForm.submit(); // Submit form
            }
        });
    });

    // Inisialisasi data-current-role saat halaman dimuat
    // agar dropdown bisa kembali ke nilai awal jika dibatalkan
    $('.role-select').each(function() {
        // Simpan nilai awal dari HTML attribute untuk digunakan sebagai current-role
        $(this).data('current-role', $(this).attr('data-current-role'));
    });
});