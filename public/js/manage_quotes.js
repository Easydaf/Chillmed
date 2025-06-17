$(document).ready(function() {
    
    const baseUrl = (typeof window.baseUrl !== 'undefined') ? window.baseUrl : 'http://localhost:8080/';

    
    const csrfName = $('meta[name="csrf-name"]').attr('content') || '';
    let csrfHash = $('meta[name="csrf-token"]').attr('content') || '';

    
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
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => { 
                        console.log("SweetAlert closed, attempting DOM update for quote ID:", quoteId); 
                       
                        const updatedText = $('#edit_quote_text').val();
                        const updatedAuthor = $('#edit_author').val() || 'Anonim';
                        
                       
                        const $quoteItem = $(`#quote-item-${quoteId}`);
                        console.log("Quote item element found:", $quoteItem.length > 0 ? true : false, $quoteItem); 
                        
                        $($quoteItem).find('strong').text(`"${updatedText}"`);
                        $($quoteItem).find('span').text(`- ${updatedAuthor} (ID: ${quoteId})`);
                        
                        
                        const escapedJsonUpdatedText = window.escapeHtml(JSON.stringify(updatedText));
                        const escapedJsonUpdatedAuthor = window.escapeHtml(JSON.stringify($('#edit_author').val() || ''));
                        $($quoteItem).find('.btn-edit').attr('onclick', `showEditQuoteModal(${quoteId}, '${escapedJsonUpdatedText}', '${escapedJsonUpdatedAuthor}')`);
                        console.log("DOM updated for quote ID:", quoteId); 
                    });
                    
                    window.hideEditQuoteModal(); 

                } else {
                   
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

    
    $('.btn-delete').on('click', function() {
        const quoteId = $(this).data('quote-id');
        const quoteText = $(this).data('quote-text'); 

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: `Anda yakin ingin menghapus quote "${quoteText}" (ID: ${quoteId})? Aksi ini tidak dapat dibatalkan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', 
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
                                $('#quote-item-' + quoteId).remove(); 
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

    $(window).on('click', function(event) {
        if ($(event.target).is('.modal')) {
            window.hideEditQuoteModal();
        }
    });
});