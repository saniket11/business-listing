function resetAllForms() {

    // ONLY business form reset
    $('.submit-data')[0].reset();

    // reset business form fields
    $('.submit-data input[name="type"]').val('add-business');

    $('#business_id').val('');

    // reset submit button only inside business form
    $('.submit-data button[type="submit"]')
        .text('Save')
        .prop('disabled', false)
        .css('pointer-events', 'auto');

    // reset rating form
    if ($('#ratingForm').length) {
        $('#ratingForm')[0].reset();

        $('#userRating').empty();

        $('#ratingValue').val('');

        initUserRating(0);
    }
}
$(document).on('click', '.editBtn', function() {

    resetAllForms();

    $('#business_id').val($(this).data('id'));
    $('#name').val($(this).data('name'));
    $('#address').val($(this).data('address'));
    $('#phone').val($(this).data('phone'));
    $('#email').val($(this).data('email'));

    // IMPORTANT
    $('.submit-data input[name="type"]').val('update-business');

    $('.submit-data .submit').text('Update');

    $('#businessModal').modal('show');
});

function initUserRating(score = 0) {

    destroyUserRating(); // ✅ force reset

    $('#userRating').raty({
        number: 5,
        score: score,
        half: true,
        starType: 'i',
        starOn: 'fa-solid fa-star',
        starOff: 'fa-regular fa-star',
        starHalf: 'fa-solid fa-star-half-stroke',
        click: function(rating) {
            $('#ratingValue').val(rating);
        }
    });
}

function initAvgRatings() {

    $('.avg-rating').each(function() {

        let score = parseFloat($(this).data('score')) || 0;

        $(this).empty();

        $(this).raty({
            readOnly: true,
            half: true,
            score: score,
            starType: 'i',
            starOn: 'fa-solid fa-star',
            starOff: 'fa-regular fa-star',
            starHalf: 'fa-solid fa-star-half-stroke'
        });
    });
}


$(document).on('click', '.rateBtn', function() {

    $('#rating_business_id').val($(this).data('id'));

    $('#ratingForm')[0].reset();

    initUserRating(0); // ⭐ always blank
    $('#ratingValue').val('');

    $('#ratingModal').modal('show');
});
$(document).on('click', '.deleteBtn', function() {

    let id = $(this).data('id');
    let name = $(this).data('name');

    $('#deleteId').val(id);
    $('#delete-modal-content').text(`Are you sure you want to delete "${name}"?`);

    $('#delete-modal').modal('show');
});

function loadBusinesses() {
    $.ajax({
        url: "ajax/business-list.php",
        type: "GET",
        dataType: "json",
        success: function(res) {

            if (res.status === true) {
                let rows = "";

                $.each(res.data, function(i, item) {
                    rows += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.address}</td>
                            <td>${item.phone}</td>
                            <td>${item.email}</td>
                             

                         
                            <td>
                                <button class="btn btn-sm btn-success editBtn"
                                    data-id="${item.id}"
                                    data-name="${item.name}"
                                    data-address="${item.address}"
                                    data-phone="${item.phone}"
                                    data-email="${item.email}">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger deleteBtn"
                                    data-id="${item.id}"
                                    data-name="${item.name}">
                                    Delete
                                </button>

                                <button class="btn btn-sm btn-warning rateBtn"
                                    data-id="${item.id}">
                                    ⭐ Rate
                                </button>
                            </td>
                            <td>
                                    <div class="avg-rating" data-score="${item.avg_rating || 0}"></div>
                            </td>
                        </tr>
                    `;
                });

                $("#businessTableBody").html(rows);
                initAvgRatings();


            }
        }
    });
}



function showModal(response, errorMessage, type) {
    if (response == '') {
        $('#success-modal').show();
        $('.success-message').text(errorMessage);
    } else {
        var responseObject = JSON.parse(response);
        // Check the errorCode
        if (responseObject.errorCode === "0000") {

            // close business modal properly
            const businessModalEl = document.getElementById('businessModal');
            const businessModal = bootstrap.Modal.getInstance(businessModalEl);
            if (businessModal) businessModal.hide();

            // show success
            const successModal = new bootstrap.Modal(
                document.getElementById('success-modal')
            );
            $('.success-message').text(responseObject.errorMessage);
            successModal.show();

            setTimeout(() => {
                successModal.hide();

                resetAllForms();
                loadBusinesses(); // ✅ THIS updates table immediately

            }, 1000);
        } else {


            $('.modal').modal('hide');
            $('#delete-modal').addClass('hide');
            $('body').append('<div class="modal-backdrop fade show"></div>');
            $('#delete-modal').css('display', 'none');
            $('.success-message').text(responseObject.errorMessage);
            $('#error-modal').modal('show');
            $('#error-modal').css('display', 'block');
            $('.error-message').addClass('success-message text-danger').text(responseObject.errorMessage);
            setTimeout(function() {
                $('.modal-backdrop').remove();
                $('.modal').removeClass('show').addClass('hide');
                $('.modal').css('display', 'none');
                $('#error-modal').hide();
                $('.error-message').removeClass('success-message').text('');
                var thisEl = $('.submit');
                thisEl.text('Submit');
                thisEl.removeAttr('disabled');
                thisEl.css('pointer-events', 'auto');
                if (type == 'login') {
                    $('.message').addClass('success-message text-danger').text(responseObject.errorMessage);
                }

            }, 2000);

        }
    }
}

function showMessage(response, errorMessage, type) {
    if (response == '') {
        $('#success-modal').show();
        $('.success-message').text(errorMessage);
    } else {
        var responseObject = JSON.parse(response);
        // Check the errorCode
        if (responseObject.errorCode == "0000") {
            $('.show-message').css({ 'color': 'green', 'font-weight': 'bold' });
            $('.show-message').text(responseObject.errorMessage);

            setTimeout(function() {
                $('.download-certificate').attr('href', 'panel/assets/images/certificates/' + responseObject.certificateName);
                $('form.certificate-generator').css('display', 'none');
                $('.download-section').css('display', 'block');
            }, 2000);
        } else {
            $('.show-message').css({ 'color': 'red', 'font-weight': 'bold' });
            $('.show-message').text(responseObject.errorMessage);
            var thisEl = $('.submit');
            thisEl.text('Verify');
            thisEl.removeAttr('disabled');
            thisEl.css('pointer-events', 'auto');
        }
    }
}

function base64EncodeUnicode(str) {
    return btoa(unescape(encodeURIComponent(str)));
}


function sendDataToServer(url, dataObject, type, dataType, errorMessage, form) {
    var formData;

    if (dataType === 'post') {
        // ✅ FIX: use current form instead of first form
        formData = new FormData(form[0]);

        var encodedData = new FormData();

        for (var [key, value] of formData.entries()) {
            if (value instanceof File) {
                encodedData.append(key, value);
            } else {
                encodedData.append(key, base64EncodeUnicode(value));
            }
        }

        formData = encodedData;
    } else {
        var encodedObject = {};
        for (var key in dataObject) {
            if (dataObject.hasOwnProperty(key)) {
                encodedObject[key] = btoa(dataObject[key]);
            }
        }
        formData = JSON.stringify(encodedObject);
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        contentType: (dataType === 'post') ? false : 'application/json',
        processData: (dataType === 'post') ? false : true,
        success: function(response) {
            showModal(response, errorMessage, type);
        },
        error: function(error) {
            showModal(error, errorMessage, type);
        }
    });
}

function sendCertificateRequestToServer(url, dataObject, type, dataType, errorMessage) {
    var formData;
    if (dataType === 'post') {
        formData = new FormData($('.certificate-generator')[0]);

        // Encode form data using btoa
        var encodedData = new FormData();
        for (var [key, value] of formData.entries()) {
            if (value instanceof File) {
                // If the value is a file, do not encode it
                encodedData.append(key, value);
            } else {
                encodedData.append(key, btoa(value));
            }
        }
        formData = encodedData;
    } else {
        // Encode JSON object data
        var encodedObject = {};
        for (var key in dataObject) {
            if (dataObject.hasOwnProperty(key)) {
                encodedObject[key] = btoa(dataObject[key]);
            }
        }
        formData = JSON.stringify(encodedObject);
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        contentType: (dataType === 'post') ? false : 'application/json',
        processData: (dataType === 'post') ? false : true,
        success: function(response) {
            showMessage(response, errorMessage, type);
        },
        error: function(error) {
            showMessage(error, errorMessage, type);
        },
        complete: function() {
            if (type === 'documents') {
                $('#loader-container').hide();
            }
        }
    });
}


$('body').on('submit', 'form.delete-data', function(e) {
    // $('.delete-btn').on('click', function(e) {
    e.preventDefault();
    var type = 'delete';
    var url, dataObject;
    var call_api = true;

    dataType = 'json';
    url = base_url + 'delete-data.php';
    dataObject = {
        id: $('input[name="deleteId"]').val(),
        mode: $('input[name = "deleteMode"]').val(),
        deleteType: $('input[name = "pageType"]').val()
    };

    $(this).find('input').each(function() {
        if ($(this).val() == '' && $(this).prop('required')) {
            call_api = false;

            return false;
        }
    });

    if (call_api) {
        var thisEl = $('.delete-modal-btn');
        thisEl.text('Please wait...');
        thisEl.attr('disabled', 'true');
        thisEl.css('pointer-events', 'none');
        sendDataToServer(url, dataObject, type, dataType);
    }
});


$('form.submit-data').on('submit', function(e) {
    e.preventDefault();

    var form = $(this);
    var type = form.find('input[name="type"]').val();
    var url, dataType = 'post';
    var call_api = true;

    url = 'ajax/ajax.php';


    // ✅ TinyMCE scoped fix
    form.find('textarea[name="roles_responsibility"]').each(function() {
        var editorId = $(this).attr('id');
        if (editorId && tinymce.get(editorId)) {
            $(this).val(
                encodeURIComponent(tinymce.get(editorId).getContent())
            );
        }
    });

    // ✅ validation
    form.find('input, textarea, select').each(function() {
        if ($(this).prop('required') && $(this).val() === '') {
            call_api = false;
            return false;
        }
    });

    if (call_api) {
        var submitBtn = form.find('button[type="submit"]');

        submitBtn.text('Please wait...');
        submitBtn.prop('disabled', true);
        submitBtn.css('pointer-events', 'none');

        // ✅ FIX: pass form here
        sendDataToServer(url, {}, type, dataType, '', form);
    }
});



$(document).on('click', '.close', function() {
    $('.modal-popup').hide();
    // $('#delete-modal').modal('hide');
});


function populateDeleteModal(id, text, pageName, deleteType) {
    var modalContent = document.getElementById('delete-modal-content');

    modalContent.innerHTML = '<form class="delete-data">' +
        '<input type="hidden" name="deleteId" value="' + id + '">' +
        '<input type="hidden" name="deleteMode" value="' + deleteType + '">' +
        '<input type="hidden" name="pageType" value="' + pageName + '">' +
        '<button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>' +
        '<div class="text-center px-5 pb-0">' +
        '<svg class="custom-alert-icon svg-danger" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M15.73 3H8.27L3 8.27v7.46L8.27 21h7.46L21 15.73V8.27L15.73 3zM12 17.3c-.72 0-1.3-.58-1.3-1.3 0-.72.58-1.3 1.3-1.3.72 0 1.3.58 1.3 1.3 0 .72-.58 1.3-1.3 1.3zm1-4.3h-2V7h2v6z"/></svg>' +
        '<h5>Danger</h5>' +
        '<p class="">' + text + '</p>' +
        '<div>' +
        '<button class="delete-btn btn btn-sm btn-danger m-1 delete-modal-btn submit" type="submit">Delete</button>' +
        '<button type="button" class="btn close btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">Close</button>' +
        '</div>' +
        '</div>' +
        '</form>';
}


$('#ratingForm').on('submit', function(e) {
    e.preventDefault();

    let rating = $('#ratingValue').val();

    if (!rating) {
        $('.error-message').text('Please select a rating');
        $('#error-modal').modal('show');
        return;
    }

    $.ajax({
        url: 'ajax/ajax.php',
        type: 'POST',
        data: {
            type: btoa('add-rating'),
            business_id: btoa($('#rating_business_id').val()),
            name: btoa($(this).find('input[name="name"]').val()),
            email: btoa($(this).find('input[name="email"]').val()),
            phone: btoa($(this).find('input[name="phone"]').val()),
            rating: btoa(rating)
        },
        success: function(response) {
            showModal(response, 'Rating submitted successfully!');
            $('#ratingModal').modal('hide');

            // RESET FORM (important)
            $('#ratingForm')[0].reset();

            // reset rating UI
            initUserRating(0);
        }
    });
});

function destroyUserRating() {
    if ($('#userRating').data('raty')) {
        $('#userRating').raty('destroy');
    }
    $('#userRating').empty();
    $('#ratingValue').val('');
}

$(document).ready(function() {
    loadBusinesses();
    initUserRating(0);
});

$('body').on('submit', '.delete-form', function(e) {

    e.preventDefault();

    let form = $(this);

    let submitBtn = form.find('.submit');

    submitBtn.text('Please wait...');
    submitBtn.prop('disabled', true);

    $.ajax({
        url: 'ajax/ajax.php',
        type: 'POST',
        data: {
            type: btoa('delete-business'),
            id: btoa($('#deleteId').val())
        },

        success: function(response) {

            showModal(response, '', 'delete');

            $('#delete-modal').modal('hide');

            loadBusinesses();

            submitBtn.text('Delete');
            submitBtn.prop('disabled', false);
        },

        error: function() {

            $('#error-modal').modal('show');

            submitBtn.text('Delete');
            submitBtn.prop('disabled', false);
        }
    });

});