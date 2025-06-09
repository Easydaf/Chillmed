// public/js/manage_quotes.js

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
                $('meta[name="csrf-token"]').attr('content', newToken);
            } else {
                const currentMetaToken = $('meta[name="csrf-token"]').attr('content');
                if (currentMetaToken && currentMetaToken !== csrfHash) {
                    csrfHash = currentMetaToken;
                }
            }
        }
    });

    // --- FUNGSI showAddQuoteModal dan hideAddQuoteModal TIDAK LAGI DIPAKAI KARENA TAMBAH PAKAI HALAMAN BARU ---
    // window.showAddQuoteModal = function() { /* ... */ }
    // window.hideAddQuoteModal = function() { /* ... */ }

    // Fungsi untuk menampilkan/menyembunyikan modal edit
    window.showEditQuoteModal = function(id, jsonText, jsonAuthor) {
        const text = JSON.parse(jsonText);
        const author = JSON.parse(jsonAuthor);

        $('#edit_quote_id').val(id);
        $('#edit_quote_text').val(text);
        $('#edit_author').val(author);
        $('#editQuoteModal').fadeIn();
    }
    window.hideEditQuoteModal = function() {
        $('#editQuoteModal').fadeOut();
        $('#editQuoteForm')[0].reset();
    }

    // --- HAPUS HANDLER SUBMIT FORM TAMBAH QUOTE INI ---
    // $('#addQuoteForm').on('submit', function(e) { /* ... */ });
    // --- AKHIR HAPUS ---

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
                    alert(response.message);
                    window.hideEditQuoteModal();
                    const updatedText = $('#edit_quote_text').val();
                    const updatedAuthor = $('#edit_author').val() || 'Anonim';
                    
                    const escapedJsonUpdatedText = window.escapeHtml(JSON.stringify(updatedText));
                    const escapedJsonUpdatedAuthor = window.escapeHtml(JSON.stringify($('#edit_author').val() || ''));

                    $(`#quote-item-${quoteId} strong`).text(`"${updatedText}"`);
                    $(`#quote-item-${quoteId} span`).text(`- ${updatedAuthor} (ID: ${quoteId})`);
                    
                    $(`#quote-item-${quoteId} button:first-child`).attr('onclick', `showEditQuoteModal(${quoteId}, '${escapedJsonUpdatedText}', '${escapedJsonUpdatedAuthor}')`);

                } else {
                    alert('Error: ' + response.message);
                    if (response.errors) {
                        let errorMsgs = '';
                        for (const key in response.errors) {
                            errorMsgs += response.errors[key] + '\n';
                        }
                        alert(errorMsgs);
                    }
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
                alert(errorMessage);
                console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
            }
        });
    });

    // Handle delete quote
    window.deleteQuote = function(id) {
        if (confirm('Anda yakin ingin menghapus quote ini?')) {
            let deleteData = {};
            
            if (csrfName && csrfHash) {
                deleteData[csrfName] = csrfHash;
            }

            const targetUrl = baseUrl + 'admin/quotes/delete/' + id;
            console.log("Sending AJAX POST to:", targetUrl);

            $.ajax({
                url: targetUrl,
                method: 'POST',
                data: deleteData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#quote-item-' + id).remove();
                        if ($('#quotes-list .data-item').length === 0) {
                            $('#quotes-list').append('<p>Belum ada quotes di database.</p>');
                        }
                    } else {
                        alert('Error: ' + response.message);
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
                    alert(errorMessage);
                    console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
                }
            });
        }
    }

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
            // Karena showAddQuoteModal sudah tidak ada, hapus panggilannya di sini
            // window.hideAddQuoteModal(); // Baris ini tidak perlu karena handler tombol 'batal' sudah memanggilnya
            window.hideEditQuoteModal();
        }
    });
});