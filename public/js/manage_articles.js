// public/js/manage_articles.js

$(document).ready(function() {
    const baseUrl = (typeof window.baseUrl !== 'undefined') ? window.baseUrl : 'http://localhost:8080/';

    // Ambil CSRF Token dan Name dari meta tag
    const csrfName = $('meta[name="csrf-name"]').attr('content') || '';
    let csrfHash = $('meta[name="csrf-token"]').attr('content') || '';

    // Perbarui CSRF Token di setiap request sukses (jika regenerate=true di Security.php)
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

    // Handle klik tombol hapus artikel
    $('.btn-delete').on('click', function() {
        const articleId = $(this).data('article-id');
        const articleTitle = $(this).data('article-title');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: `Anda yakin ingin menghapus artikel "${articleTitle}" (ID: ${articleId})? Aksi ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, kirim form POST untuk penghapusan (full page refresh)
                const deleteForm = $(`<form action="${baseUrl}admin/articles/delete/${articleId}" method="post"></form>`);
                
                if (csrfName && csrfHash) {
                    deleteForm.append(`<input type="hidden" name="${csrfName}" value="${csrfHash}">`);
                }
                $('body').append(deleteForm);
                deleteForm.submit(); // Submit form
            }
        });
    });

    window.escapeHtml = function(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});