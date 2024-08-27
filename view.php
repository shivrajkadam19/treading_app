<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Files</title>
</head>
<body>
    <h1>Uploaded Files</h1>
    <div id="fileList"></div>

    <script>
        async function fetchFiles() {
            try {
                const response = await fetch('http://localhost/treading/api.get.uploads.php');
                const result = await response.json();

                if (result.status === 'success') {
                    const files = result.data;
                    const fileList = document.getElementById('fileList');

                    files.forEach(file => {
                        const fileItem = document.createElement('div');
                        fileItem.innerHTML = `
                            <h3>${file.title}</h3>
                            <p>Description: ${file.description}</p>
                            <img src="uploads/${file.file}">
                            <p>Uploaded on: ${file.created_at}</p>
                            <hr>
                        `;
                        fileList.appendChild(fileItem);
                    });
                } else {
                    document.getElementById('fileList').innerText = result.message;
                }
            } catch (error) {
                document.getElementById('fileList').innerText = 'Error fetching files';
            }
        }

        window.onload = fetchFiles;
    </script>
</body>
</html>
