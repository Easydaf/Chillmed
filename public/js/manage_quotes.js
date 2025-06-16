// public/js/manage_quotes.js

$(document).ready(function() {
    // Pastikan baseUrl ini dideklarasikan di HTML sebelum script ini dimuat
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

    // Fungsi untuk menampilkan/menyembunyikan modal edit
    window.showEditQuoteModal = function(id, jsonText, jsonAuthor) { // Terima sebagai string JSON
        const text = JSON.parse(jsonText);   // Parse string JSON menjadi string JS murni
        const author = JSON.parse(jsonAuthor); // Parse string JSON menjadi string JS murni

        $('#edit_quote_id').val(id);
        $('#edit_quote_text').val(text);
        $('#edit_author').val(author);
        $('#editQuoteModal').fadeIn();
    }
    window.hideEditQuoteModal = function() {
        $('#editQuoteModal').fadeOut();
        $('#editQuoteForm')[0].reset();
    }

    // Handle submit form edit quote
    $('#editQuoteForm').on('submit', function(e) {
        e.preventDefault();
        const quoteId = $('#edit_quote_id').val();
        let formData = $(this).serializeArray();
        
        if (csrfName && csrfHash) {
            formData.push({name: csrfName, value: csrfHash});
        }

        const targetUrl = baseUrl + 'admin/quotes/edit/' + quoteId;
        console.log("Sending AJAX POST to:", targetUrl);

        $.ajax({
            url: targetUrl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // UBAH INI: Gunakan SweetAlert untuk sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => { // Callback setelah SweetAlert hilang
                        console.log("SweetAlert closed, attempting DOM update for quote ID:", quoteId); // DEBUG LOG
                        // Perbarui DOM (HTML) secara dinamis di sini
                        const updatedText = $('#edit_quote_text').val();
                        const updatedAuthor = $('#edit_author').val() || 'Anonim';
                        
                        // Perbarui teks di elemen <strong> dan <span> yang relevan
                        const $quoteItem = $(`#quote-item-${quoteId}`);
                        console.log("Quote item element found:", $quoteItem.length > 0 ? true : false, $quoteItem); // DEBUG LOG
                        
                        $($quoteItem).find('strong').text(`"${updatedText}"`);
                        $($quoteItem).find('span').text(`- ${updatedAuthor} (ID: ${quoteId})`);
                        
                        // PERBAIKAN PENTING: Update juga atribut onclick pada tombol edit
                        // agar data yang di-load ke modal di kemudian hari adalah versi terbaru.
                        const escapedJsonUpdatedText = window.escapeHtml(JSON.stringify(updatedText));
                        const escapedJsonUpdatedAuthor = window.escapeHtml(JSON.stringify($('#edit_author').val() || ''));
                        $($quoteItem).find('.btn-edit').attr('onclick', `showEditQuoteModal(${quoteId}, '${escapedJsonUpdatedText}', '${escapedJsonUpdatedAuthor}')`);
                        console.log("DOM updated for quote ID:", quoteId); // DEBUG LOG
                    });
                    
                    window.hideEditQuoteModal(); // Sembunyikan modal segera setelah AJAX sukses

                } else {
                    // Gunakan SweetAlert untuk gagal
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Terjadi kesalahan server: ' + xhr.status + ' ' + xhr.statusText;
                if (xhr.responseText) {
                    try {
                        const jsonResponse = JSON.parse(xhr.responseText);
                        if (jsonResponse.message) {
                            errorMessage += '. Detail: ' + jsonResponse.message;
                        } else if (jsonResponse.detail) {
                            errorMessage += '. Detail: ' + jsonResponse.detail;
                        }
                    } catch (e) {
                        errorMessage += '. Response: ' + xhr.responseText.substring(0, 100) + '...';
                    }
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    showConfirmButton: false,
                    timer: 4000
                });
                console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
            }
        });
    });

    // Handle delete quote (Sudah menggunakan SweetAlert sebelumnya, tidak perlu perubahan)
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
                let deleteData = {};
                
                if (csrfName && csrfHash) {
                    deleteData[csrfName] = csrfHash;
                }

                const targetUrl = baseUrl + 'admin/quotes/delete/' + quoteId;
                console.log("Sending AJAX POST to:", targetUrl);

                $.ajax({
                    url: targetUrl,
                    method: 'POST',
                    data: deleteData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            ).then(() => {
                                $('#quote-item-' + quoteId).remove(); // Hapus elemen dari DOM
                                if ($('#quotes-list .data-item').length === 0) {
                                    $('#quotes-list').append('<p>Belum ada quotes di database.</p>');
                                }
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan server: ' + xhr.status + ' ' + xhr.statusText;
                        if (xhr.responseText) {
                            try {
                                const jsonResponse = JSON.parse(xhr.responseText);
                                if (jsonResponse.message) {
                                    errorMessage += '. Detail: ' + jsonResponse.message;
                                } else if (jsonResponse.detail) {
                                    errorMessage += '. Detail: ' + jsonResponse.detail;
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
                    }
                });
            }
        });
    });

    // Fungsi helper untuk menghindari masalah quote di HTML attribute (tampilan)
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

    // Tutup modal saat klik di luar area konten modal
    $(window).on('click', function(event) {
        if ($(event.target).is('.modal')) {
            window.hideEditQuoteModal();
        }
    });
});