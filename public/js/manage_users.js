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

        // Simpan role saat ini untuk kemungkinan rollback jika konfirmasi dibatalkan
        $(this).data('current-role', $(this).find('option:selected').data('initial-role') || $(this).val());


        if (confirm(`Anda yakin ingin mengubah role ${userName} menjadi ${newRole}?`)) {
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
                        alert(response.message);
                        // Perbarui data-current-role di dropdown setelah sukses
                        $(`.role-select[data-user-id="${userId}"]`).data('current-role', newRole);
                    } else {
                        alert('Error: ' + response.message);
                        // Rollback dropdown jika gagal
                        $(`.role-select[data-user-id="${userId}"]`).val($(`.role-select[data-user-id="${userId}"]`).data('current-role'));
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
                    alert(errorMessage);
                    console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
                    // Rollback dropdown jika ada error AJAX
                    $(`.role-select[data-user-id="${userId}"]`).val($(`.role-select[data-user-id="${userId}"]`).data('current-role'));
                }
            });
        } else {
            // Jika user membatalkan, kembalikan pilihan dropdown ke nilai semula
            $(this).val($(this).data('current-role'));
        }
    });

    // Fungsi untuk inisialisasi data-current-role saat halaman dimuat
    $('.role-select').each(function() {
        $(this).data('current-role', $(this).val());
    });

    // Catatan: Fungsi hapus user tidak di sini, karena form delete sudah langsung di HTML
    // dengan onsubmit="return confirm(...)"
});