jQuery(document).ready(function($) {
    var $form = $('#cpde_edit_form'),
        $status = $('<div class="cpde-status"></div>'),
        $previewCard = $('.cpde-preview-card'),
        searchTimer;

    // Add status element
    $form.prepend($status);

    // Tab switching
    $('.cpde-tab-button').on('click', function() {
        var tab = $(this).data('tab');
        
        // Update buttons
        $('.cpde-tab-button').removeClass('active');
        $(this).addClass('active');
        
        // Update panels
        $('.cpde-search-panel').removeClass('active');
        $('#cpde-' + tab + '-search').addClass('active');
        
        // Clear any existing search/results
        clearSearch();
    });

    // Handle ID search
    $('#cpde_fetch_post').on('click', function() {
        var postId = $('#cpde_post_id').val();
        if (postId) {
            fetchPost(postId);
        }
    });

    $('#cpde_post_id').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#cpde_fetch_post').click();
        }
    });

    // Handle title search
    $('#cpde_post_search').on('input', function() {
        var query = $(this).val();
        clearTimeout(searchTimer);
        
        if (query.length >= 2) {
            searchTimer = setTimeout(function() {
                searchPosts(query);
            }, 500);
        } else {
            $('#cpde_search_results').removeClass('active').empty();
        }
    });

    // Handle search result selection
    $(document).on('click', '.cpde-search-item', function() {
        var postId = $(this).data('id');
        $('#cpde_post_search').val($(this).find('.cpde-search-item-title').text());
        $('#cpde_search_results').removeClass('active');
        fetchPost(postId);
    });

    // Clear search and results
    function clearSearch() {
        $('#cpde_post_id').val('');
        $('#cpde_post_search').val('');
        $('#cpde_search_results').removeClass('active').empty();
        $previewCard.hide();
        $form.hide();
    }

    // Search posts by title
    function searchPosts(query) {
        $.ajax({
            url: cpdeAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'cpde_search_posts',
                nonce: cpdeAjax.nonce,
                search: query
            },
            success: function(response) {
                if (response.success && response.data.results.length) {
                    var $results = $('#cpde_search_results').empty();
                    
                    response.data.results.forEach(function(post) {
                        $results.append(
                            '<div class="cpde-search-item" data-id="' + post.id + '">' +
                            '<div class="cpde-search-item-title">' + 
                                '<strong>#' + post.id + '</strong> - ' + post.title +
                            '</div>' +
                            '<div class="cpde-search-item-meta">' +
                                '<span class="author">By ' + post.author + '</span>' +
                                '<span class="date">Published: ' + post.post_date + '</span>' +
                                '<span class="status">Status: ' + post.status + '</span>' +
                            '</div>' +
                            '</div>'
                        );
                    });
                    
                    $results.addClass('active');
                } else {
                    $('#cpde_search_results').removeClass('active').html(
                        '<div class="cpde-no-results">No posts found matching your search.</div>'
                    );
                }
            },
            error: function() {
                $('#cpde_search_results').removeClass('active').html(
                    '<div class="cpde-no-results error">Error occurred while searching. Please try again.</div>'
                );
            }
        });
    }

    // Fetch post by ID
    function fetchPost(postId) {
        $.ajax({
            url: cpdeAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'cpde_fetch_post',
                nonce: cpdeAjax.nonce,
                post_id: postId
            },
            success: function(response) {
                if (response.success && response.data) {
                    updatePreviewCard(response.data);
                    updateFormValues(response.data);
                    $('#cpde_selected_post_id').val(postId);
                    $previewCard.show();
                    $form.show();
                } else {
                    alert(cpdeAjax.strings.notFound);
                }
            },
            error: function() {
                alert(cpdeAjax.strings.error);
            }
        });
    }

    // Update preview card with post details
    function updatePreviewCard(data) {
        // Update image
        var imageHtml = data.featured_image 
            ? '<img src="' + data.featured_image + '" alt="' + data.title + '">'
            : '<div class="no-image"></div>';
        $('.cpde-preview-image').html(imageHtml);

        // Update title and meta
        $('.cpde-preview-title h2').text(data.title);
        $('.cpde-preview-meta').html(
            '<span class="post-type">' + (data.post_type || 'Post') + '</span>' +
            '<span class="status">' + (data.status || 'Published') + '</span>' +
            (data.author ? '<span class="author">By ' + data.author + '</span>' : '')
        );

        // Update content
        if (data.excerpt) {
            $('.cpde-preview-excerpt').html(data.excerpt).show();
        } else {
            $('.cpde-preview-excerpt').hide();
        }
        
        // Update details
        var categoriesHtml = data.categories && data.categories.length 
            ? '<div class="categories">Categories: ' + data.categories.join(', ') + '</div>'
            : '';
        
        $('.cpde-preview-details').html(
            '<div class="post-dates">' +
            '<div>Published: ' + formatDate(data.post_date) + '</div>' +
            '<div>Last Modified: ' + formatDate(data.post_modified) + '</div>' +
            '</div>' +
            categoriesHtml +
            (data.comment_count ? '<div class="comments">Comments: ' + data.comment_count + '</div>' : '')
        );

        // Update view link
        if (data.permalink) {
            $('.cpde-preview-footer .view-post').attr('href', data.permalink).show();
        } else {
            $('.cpde-preview-footer .view-post').hide();
        }

        // Show the card
        $previewCard.show();
    }

    // Update form values
    function updateFormValues(data) {
        $('#cpde_published_date').val(data.post_date);
        $('#cpde_modified_date').val(data.post_modified);
    }

    // Format date for display
    function formatDate(dateString) {
        if (!dateString) return '';
        var date = new Date(dateString.replace('T', ' '));
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    }

    // Handle form submission
    $form.on('submit', function(e) {
        e.preventDefault();
        saveDates();
    });

    // Save dates
    function saveDates() {
        $status.html(cpdeAjax.strings.saving).addClass('saving').removeClass('error success').show();
        
        $.ajax({
            url: cpdeAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'cpde_save_dates',
                nonce: cpdeAjax.nonce,
                post_id: $('#cpde_selected_post_id').val(),
                published_date: $('#cpde_published_date').val(),
                modified_date: $('#cpde_modified_date').val()
            },
            success: function(response) {
                if (response.success) {
                    $status.html(cpdeAjax.strings.saved).addClass('success').removeClass('saving error');
                    setTimeout(function() {
                        $status.fadeOut();
                    }, 2000);
                } else {
                    $status.html(response.data).addClass('error').removeClass('saving');
                }
            },
            error: function() {
                $status.html(cpdeAjax.strings.error).addClass('error').removeClass('saving');
            }
        });
    }
}); 