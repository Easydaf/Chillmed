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
        const userName = $(`#user-item-${userId} td[data-label="Nama"]`).text(); // Ambil nama user

        // Simpan role saat ini dari data-initial-role yang ada di HTML
        const currentRole = $(this).data('initial-role'); 
        // Tidak perlu set data-current-role di sini lagi, cukup pakai data-initial-role dari HTML
        // $(this).data('current-role', currentRole); // Baris ini tidak lagi diperlukan

        // Ganti confirm() dengan SweetAlert untuk konfirmasi perubahan role
        Swal.fire({
            title: 'Konfirmasi Perubahan Role',
            html: `Anda yakin ingin mengubah role <b>${userName}</b> menjadi <b>${newRole}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user mengkonfirmasi
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
                            // Ganti alert() sukses dengan SweetAlert
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            });
                            // Perbarui data-initial-role di dropdown setelah sukses
                            $(`.role-select[data-user-id="${userId}"]`).data('initial-role', newRole);
                        } else {
                            // Ganti alert() error dengan SweetAlert
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error: ' + response.message,
                                icon: 'error'
                            });
                            // Rollback dropdown jika gagal, gunakan data-initial-role
                            $(`.role-select[data-user-id="${userId}"]`).val($(`.role-select[data-user-id="${userId}"]`).data('initial-role'));
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
                        // Ganti alert() error AJAX dengan SweetAlert
                        Swal.fire({
                            title: 'Kesalahan Jaringan!',
                            text: errorMessage,
                            icon: 'error'
                        });
                        console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
                        // Rollback dropdown jika ada error AJAX, gunakan data-initial-role
                        $(`.role-select[data-user-id="${userId}"]`).val($(`.role-select[data-user-id="${userId}"]`).data('initial-role'));
                    }
                });
            } else {
                // Jika user membatalkan, kembalikan pilihan dropdown ke nilai semula (data-initial-role)
                $(this).val($(this).data('initial-role'));
            }
        });
    });

    // Handle klik tombol hapus menggunakan SweetAlert
    $('.delete-user-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah submit form default
        const form = $(this);
        const userId = form.data('user-id');
        const userName = form.data('user-name');

        Swal.fire({
            title: 'Konfirmasi Hapus Pengguna',
            html: `Anda yakin ingin menghapus pengguna <b>${userName}</b>? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user mengkonfirmasi, kirim form secara manual (AJAX)
                // Pastikan server Anda mendukung metode DELETE atau POST dengan method override
                // Untuk kasus ini, karena form methodnya POST, kita lanjutkan POST request.
                const targetUrl = form.attr('action'); // Ambil action URL dari form
                let formData = form.serializeArray(); // Serialisasi data form
                
                // Tambahkan CSRF token jika belum ada di formData
                let csrfFound = false;
                for (let i = 0; i < formData.length; i++) {
                    if (formData[i].name === csrfName) {
                        formData[i].value = csrfHash;
                        csrfFound = true;
                        break;
                    }
                }
                if (!csrfFound && csrfName && csrfHash) {
                    formData.push({ name: csrfName, value: csrfHash });
                }

                $.ajax({
                    url: targetUrl,
                    method: 'POST', // Menggunakan method POST sesuai form HTML Anda
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Dihapus!',
                                response.message,
                                'success'
                            );
                            // Hapus baris pengguna dari DOM
                            $(`#user-item-${userId}`).remove();
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Error: ' + response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus: ' + xhr.status + ' ' + xhr.statusText;
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
                            'Kesalahan!',
                            errorMessage,
                            'error'
                        );
                        console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
                    }
                });
            }
        });
    });

    // Inisialisasi data-initial-role saat halaman dimuat
    // agar SweetAlert punya nilai yang benar untuk rollback
    $('.role-select').each(function() {
        const initialRole = $(this).val();
        $(this).data('initial-role', initialRole);
    });
});