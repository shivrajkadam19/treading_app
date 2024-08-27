<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Upload a File</h2>
        <form id="uploadForm">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Select File</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <div class="mb-3">
                <label for="creation_date" class="form-label">Creation Date</label>
                <input type="datetime-local" class="form-control" id="creation_date" name="creation_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <div id="response" class="mt-4"></div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                // Create a FormData object
                var formData = new FormData(this);

                // Format the creation_date to match MySQL DATETIME format
                var creationDate = $('#creation_date').val();
                console.log(creationDate)
                if (creationDate) {
                    creationDate = creationDate.replace("T", " ") + ":00"; // Convert to YYYY-MM-DD HH:MM:SS
                    formData.set('creation_date', creationDate);
                }

                $.ajax({
                    url: 'http://localhost/treading/api.upload.php', // Replace with your actual API URL
                    type: 'POST',
                    data: formData,
                    contentType: false, // Prevent jQuery from setting Content-Type header
                    processData: false, // Prevent jQuery from processing the data
                    success: function(response) {
                        console.log(response); // Log the entire response
                        if (response.message) {
                            $('#response').html('<div class="alert alert-success">' + response.message + '</div>');
                        } else {
                            $('#response').html('<div class="alert alert-warning">No message received from server.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // Log the error response
                        var err;
                        try {
                            err = JSON.parse(xhr.responseText);
                        } catch (e) {
                            err = {
                                message: 'An unknown error occurred.'
                            };
                        }
                        $('#response').html('<div class="alert alert-danger">' + (err.message || 'An unknown error occurred.') + '</div>');
                    }
                });
            });
        });
    </script>
</body>

</html>