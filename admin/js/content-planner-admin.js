// Content Gap Analysis AJAX Request
$('#gap-analysis-button').on('click', function () {
    $('#gap-analysis-results').html('<p>Loading...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_gap_analysis',
            nonce: contentPlannerNonce
        },
        success: function (response) {
            if (response.success) {
                let resultsHTML = '<ul>';
                $.each(response.data, function (index, keyword) {
                    resultsHTML += '<li>' + keyword + '</li>';
                });
                resultsHTML += '</ul>';
                $('#gap-analysis-results').html(resultsHTML);
            } else {
                $('#gap-analysis-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#gap-analysis-results').html('<p>Error performing gap analysis.</p>');
        }
    });
});

// SERP Analysis AJAX Request
$('#serp-analysis-button').on('click', function () {
    const keyword = $('#serp-keyword').val();

    if (!keyword) {
        alert('Please enter a keyword');
        return;
    }

    $('#serp-analysis-results').html('<p>Analyzing...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_serp_analysis',
            nonce: contentPlannerNonce,
            keyword: keyword
        },
        success: function (response) {
            if (response.success) {
                $('#serp-analysis-results').html('<pre>' + response.data + '</pre>');
            } else {
                $('#serp-analysis-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#serp-analysis-results').html('<p>Error performing SERP analysis.</p>');
        }
    });
});
// Generate Content Draft AJAX Request
$('#generate-draft-button').on('click', function () {
    const keyword = $('#content-keyword').val();
    const tone = $('#tone').val();

    if (!keyword) {
        alert('Please enter a keyword');
        return;
    }

    $('#content-draft-results').html('<p>Generating content draft...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_generate_draft',
            nonce: contentPlannerNonce,
            keyword: keyword,
            tone: tone
        },
        success: function (response) {
            if (response.success) {
                $('#content-draft-results').html('<pre>' + response.data + '</pre>');
            } else {
                $('#content-draft-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#content-draft-results').html('<p>Error generating draft.</p>');
        }
    });
});

// Generate SEO Metadata AJAX Request
$('#generate-metadata-button').on('click', function () {
    const keyword = $('#metadata-keyword').val();

    if (!keyword) {
        alert('Please enter a keyword');
        return;
    }

    $('#metadata-results').html('<p>Generating metadata...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_generate_metadata',
            nonce: contentPlannerNonce,
            keyword: keyword
        },
        success: function (response) {
            if (response.success) {
                const metadata = response.data;
                $('#metadata-results').html(`
                    <p><strong>Title:</strong> ${metadata.title}</p>
                    <p><strong>Meta Description:</strong> ${metadata.meta_description}</p>
                    <p><strong>Alt Text:</strong> ${metadata.alt_text}</p>
                `);
            } else {
                $('#metadata-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#metadata-results').html('<p>Error generating metadata.</p>');
        }
    });
});
// Generate Content Draft AJAX Request
$('#generate-draft-button').on('click', function () {
    const keyword = $('#content-keyword').val();
    const tone = $('#tone').val();

    if (!keyword) {
        alert('Please enter a keyword');
        return;
    }

    $('#content-draft-results').html('<p>Generating content draft...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_generate_draft',
            nonce: contentPlannerNonce,
            keyword: keyword,
            tone: tone
        },
        success: function (response) {
            if (response.success) {
                $('#content-draft-results').html('<pre>' + response.data + '</pre>');
            } else {
                $('#content-draft-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#content-draft-results').html('<p>Error generating draft.</p>');
        }
    });
});

// Generate SEO Metadata AJAX Request
$('#generate-metadata-button').on('click', function () {
    const keyword = $('#metadata-keyword').val();

    if (!keyword) {
        alert('Please enter a keyword');
        return;
    }

    $('#metadata-results').html('<p>Generating metadata...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_generate_metadata',
            nonce: contentPlannerNonce,
            keyword: keyword
        },
        success: function (response) {
            if (response.success) {
                const metadata = response.data;
                $('#metadata-results').html(`
                    <p><strong>Title:</strong> ${metadata.title}</p>
                    <p><strong>Meta Description:</strong> ${metadata.meta_description}</p>
                    <p><strong>Alt Text:</strong> ${metadata.alt_text}</p>
                `);
            } else {
                $('#metadata-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#metadata-results').html('<p>Error generating metadata.</p>');
        }
    });
});
// SEO Checklist AJAX Request
$('#seo-checklist-button').on('click', function () {
    const postId = $('#post-id').val();

    if (!postId) {
        alert('Please enter a Post ID');
        return;
    }

    $('#seo-checklist-results').html('<p>Analyzing...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_seo_checklist',
            nonce: contentPlannerNonce,
            post_id: postId
        },
        success: function (response) {
            if (response.success) {
                const results = response.data;
                let resultsHTML = '<ul>';
                for (const [key, value] of Object.entries(results)) {
                    resultsHTML += `<li>${key}: ${value}</li>`;
                }
                resultsHTML += '</ul>';
                $('#seo-checklist-results').html(resultsHTML);
            } else {
                $('#seo-checklist-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#seo-checklist-results').html('<p>Error performing SEO analysis.</p>');
        }
    });
});

// Internal Linking Suggestions AJAX Request
$('#internal-links-button').on('click', function () {
    const postId = $('#post-id-links').val();

    if (!postId) {
        alert('Please enter a Post ID');
        return;
    }

    $('#internal-links-results').html('<p>Fetching suggestions...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_internal_links',
            nonce: contentPlannerNonce,
            post_id: postId
        },
        success: function (response) {
            if (response.success) {
                const links = response.data;
                let resultsHTML = '<ul>';
                links.forEach(link => {
                    resultsHTML += `<li><a href="${link.url}" target="_blank">${link.title}</a></li>`;
                });
                resultsHTML += '</ul>';
                $('#internal-links-results').html(resultsHTML);
            } else {
                $('#internal-links-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#internal-links-results').html('<p>Error fetching internal links.</p>');
        }
    });
});
// Keyword Tracking AJAX Request
$('#keyword-tracking-button').on('click', function () {
    const keyword = $('#keyword').val();

    if (!keyword) {
        alert('Please enter a keyword');
        return;
    }

    $('#keyword-tracking-results').html('<p>Tracking keyword...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_keyword_tracking',
            nonce: contentPlannerNonce,
            keyword: keyword
        },
        success: function (response) {
            if (response.success) {
                let resultsHTML = '<ul>';
                response.data.forEach((item) => {
                    resultsHTML += `<li>Query: ${item.keys[0]}, Impressions: ${item.impressions}</li>`;
                });
                resultsHTML += '</ul>';
                $('#keyword-tracking-results').html(resultsHTML);
            } else {
                $('#keyword-tracking-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#keyword-tracking-results').html('<p>Error tracking keyword.</p>');
        }
    });
});
// Content Performance AJAX Request
$('#content-performance-button').on('click', function () {
    const url = $('#content-url').val();

    if (!url) {
        alert('Please enter a URL');
        return;
    }

    $('#content-performance-results').html('<p>Analyzing content performance...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_content_performance',
            nonce: contentPlannerNonce,
            url: url
        },
        success: function (response) {
            if (response.success) {
                const data = response.data[0].metrics;
                $('#content-performance-results').html(`
                    <p><strong>Page Views:</strong> ${data[0]}</p>
                    <p><strong>Average Time on Page:</strong> ${data[1]}</p>
                    <p><strong>Bounce Rate:</strong> ${data[2]}%</p>
                `);
            } else {
                $('#content-performance-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#content-performance-results').html('<p>Error fetching performance data.</p>');
        }
    });
});
// Competitor Keyword Analysis AJAX Request
$('#competitor-keyword-button').on('click', function () {
    const url = $('#competitor-url').val();

    if (!url) {
        alert('Please enter a competitor URL');
        return;
    }

    $('#competitor-keyword-results').html('<p>Analyzing competitor keywords...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_competitor_keywords',
            nonce: contentPlannerNonce,
            competitor_url: url
        },
        success: function (response) {
            if (response.success) {
                let resultsHTML = '<ul>';
                response.data.forEach((item) => {
                    resultsHTML += `<li>Keyword: ${item.query}, Impressions: ${item.impressions}, Position: ${item.position}</li>`;
                });
                resultsHTML += '</ul>';
                $('#competitor-keyword-results').html(resultsHTML);
            } else {
                $('#competitor-keyword-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#competitor-keyword-results').html('<p>Error analyzing competitor keywords.</p>');
        }
    });
});

// Backlink Opportunities AJAX Request
$('#backlink-opportunity-button').on('click', function () {
    const url = $('#competitor-url-backlink').val();

    if (!url) {
        alert('Please enter a competitor URL');
        return;
    }

    $('#backlink-opportunity-results').html('<p>Fetching backlink opportunities...</p>');

    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'content_planner_backlink_opportunities',
            nonce: contentPlannerNonce,
            competitor_url: url
        },
        success: function (response) {
            if (response.success) {
                let resultsHTML = '<ul>';
                response.data.forEach((item) => {
                    resultsHTML += `<li><a href="${item.url}" target="_blank">${item.domain}</a></li>`;
                });
                resultsHTML += '</ul>';
                $('#backlink-opportunity-results').html(resultsHTML);
            } else {
                $('#backlink-opportunity-results').html('<p>' + response.data + '</p>');
            }
        },
        error: function () {
            $('#backlink-opportunity-results').html('<p>Error fetching backlink opportunities.</p>');
        }
    });
});
