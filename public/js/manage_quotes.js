// public/js/manage_quotes.js

$(document).ready(function() {
    // baseUrl dibutuhkan untuk membangun URL form submit
    const baseUrl = (typeof window.baseUrl !== 'undefined') ? window.baseUrl : 'http://localhost:8080/';

    // Ambil CSRF Token dan Name dari meta tag
    const csrfName = $('meta[name="csrf-name"]').attr('content') || '';
    let csrfHash = $('meta[name="csrf-token"]').attr('content') || '';

    // Update CSRF token on AJAX complete (ini tidak lagi relevan jika tidak ada AJAX,
    // tapi biarkan saja untuk berjaga-jaga jika ada AJAX di masa depan)
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.type === 'POST' || settings.type === 'post') {
            const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
            if (newToken && newToken !== csrfHash) {
                csrfHash = newToken;
                $('meta[name="csrf-token"]').attr('content', newToken);
            } else {
                const currentMetaToken = $('meta[name="csrf-token"]').attr('content');
                if (currentMetaToken && currentMetaToken !== csrfHash) {
                    csrfHash = currentMetaToken;
                }
            }
        }
    });

    // Handle klik tombol hapus quote (SweetAlert Konfirmasi)
    $('.btn-delete').on('click', function() {
        const quoteId = $(this).data('quote-id');
        const quoteText = $(this).data('quote-text'); // Ambil teks quote untuk konfirmasi

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: `Anda yakin ingin menghapus quote "${quoteText}" (ID: ${quoteId})? Aksi ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Warna merah untuk hapus
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, kirim form POST untuk penghapusan (full page refresh)
                const deleteForm = $(`<form action="${baseUrl}admin/quotes/delete/${quoteId}" method="post"></form>`);
                
                if (csrfName && csrfHash) {
                    deleteForm.append(`<input type="hidden" name="${csrfName}" value="${csrfHash}">`);
                }
                $('body').append(deleteForm);
                deleteForm.submit(); // Submit form
            }
        });
    });

    // Tampilkan SweetAlert dari flashdata saat halaman dimuat
    const successMessage = $('.alert.success').text().trim();
    const errorMessage = $('.alert.error').text().trim();
    const errorValidation = $('.alert.error ul').html();

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage,
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            $('.alert.success').remove();
        });
    }
    if (errorValidation) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: errorValidation,
            showConfirmButton: false,
            timer: 4000
        }).then(() => {
            $('.alert.error').remove();
        });
    } else if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage,
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            $('.alert.error').remove();
        });
    }
});