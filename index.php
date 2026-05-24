<?php
include 'config/db.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="">
    <meta name="Author" content="Logo">
    <meta name="keywords" content="">
    <title>Business Listing </title>
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container py-4">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Business Listing</h2>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#businessModal">
                    Add Business
                </button>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Actions</th>
                        <th>Average Rating</th>

                    </tr>
                </thead>
                <tbody id="businessTableBody"></tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="businessModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <form class="submit-data">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">🏢 Business Form</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">

                        <input type="hidden" name="type" value="add-business">
                        <input type="hidden" name="mode" value="add">
                        <input type="hidden" name="id" value="" id="business_id"> 

                        <div class="mb-3">
                            <label class="form-label">Business Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Enter business name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3"
                                placeholder="Enter full address" required></textarea>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control"
                                    placeholder="9876543210" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" minlength="10" maxlength="10" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="example@mail.com" required>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn submit btn-success px-4">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="ratingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <form id="ratingForm">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">⭐ Submit Rating</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">

                        <input type="hidden" name="business_id" id="rating_business_id">

                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone number"
                                required>
                        </div>

                        <div id="userRating"></div>
                        <input type="hidden" name="rating" id="ratingValue">

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- SUCESS MODAL -->

    <div class="modal fade" id="success-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center">

            <div class="modal-header border-0 justify-content-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width:60px;height:60px;">
                ✓
                </div>
            </div>

            <div class="modal-body px-4 pb-4">
                <h4 class="text-success fw-bold">Success</h4>
                <p class="success-message mb-4">Data saved successfully!</p>

                <button type="button" class="btn btn-success px-4"
                        data-bs-dismiss="modal">
                Close
                </button>
            </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="error-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center">

            <div class="modal-header border-0 justify-content-center">
                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width:60px;height:60px;">
                !
                </div>
            </div>

            <div class="modal-body px-4 pb-4">
                <h4 class="text-danger fw-bold">Error</h4>
                <p class="error-message mb-4">Something went wrong!</p>

                <button type="button" class="btn btn-danger px-4"
                        data-bs-dismiss="modal">
                Close
                </button>
            </div>

            </div>
        </div>
    </div>


<div class="modal fade" id="delete-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content border-0 shadow-lg rounded-4 text-center">

      <form class="delete-form">

        <div class="modal-header border-0 justify-content-center">
          <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
               style="width:60px;height:60px;">
            ⚠
          </div>
        </div>

        <div class="modal-body px-4">
          <h4 class="fw-bold">Confirm Delete</h4>

          <p class="deleteText" id="delete-modal-content">
            Are you sure you want to delete this record?
          </p>

          <!-- hidden fields for your submit-data system -->
          <input type="hidden" name="type" value="delete-business">
          <input type="hidden" name="id" id="deleteId">
        </div>

        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Cancel
          </button>

          <button type="submit" class="btn btn-danger submit">
            Delete
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/jquery.raty.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>