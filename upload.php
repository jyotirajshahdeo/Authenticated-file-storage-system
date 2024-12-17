<?php
// upload.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;

    // Create uploads directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Check if file already exists
 /*   if (file_exists($targetFile)) {
        echo json_encode(["status" => "error", "message" => "Sorry, file already exists."]);
        exit;
    }
*/
    // Check file size (limit to 10MB)
    if ($_FILES["file"]["size"] > 100000000000000) {
        echo json_encode(["status" => "error", "message" => "Sorry, your file is too large."]);
        exit;
    }

    // Allow certain file formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'pdf', 'doc', 'docx'];
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["status" => "error", "message" => "Sorry, only JPG, PNG, GIF, MP4, AVI, PDF & DOC files are allowed."]);
        exit;
    }

    // Check if everything is ok to upload
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            echo json_encode(["status" => "success", "file" => $targetFile]); // Return the file path for download link
        } else {
            echo json_encode(["status" => "error", "message" => "Sorry, there was an error uploading your file."]);
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        #progressContainer {
            margin-top: 20px;
            font-size: 14px;
        }

        .file-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-item a {
            color: #2196F3;
            text-decoration: none;
        }
        
        .file-item a:hover {
            text-decoration: underline;
        }
        
        input[type="file"] {
            margin-bottom: 10px;
        }
        
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1976D2;
        }

        .progress-bar {
            width: 100%;
            height: 5px;
            background-color: #ddd;
            border-radius: 5px;
            overflow: hidden; 
        }

        .progress-bar div {
            height: 100%;
            background-color: #2196F3; 
            width: 0%;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>File Upload Dashboard</h1>
        <input type="file" id="fileInput" multiple>
        <button id="uploadButton">Upload</button>
        
        <div id="progressContainer"></div>
        
        <div id="uploadedFiles"></div>
    </div>

    <script>
        const uploadButton = document.getElementById('uploadButton');
        const fileInput = document.getElementById('fileInput');
        const progressContainer = document.getElementById('progressContainer');
        const uploadedFiles = document.getElementById('uploadedFiles');

        uploadButton.addEventListener('click', () => {
            const files = fileInput.files;

            Array.from(files).forEach(file => {
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('file', file);

                xhr.open('POST', '', true); // POST to the same page

                // Create a progress bar for each file
                const progressBarContainer = document.createElement('div');
                progressBarContainer.className = 'progress-bar';
                const progressBar = document.createElement('div');
                progressBarContainer.appendChild(progressBar);
                progressContainer.appendChild(progressBarContainer);

                xhr.upload.onprogress = (event) => {
                    const percentComplete = (event.loaded / event.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                    progressContainer.innerHTML += `<div>${file.name}: ${percentComplete.toFixed(2)}%</div>`;
                };

                xhr.onload = () => {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        uploadedFiles.innerHTML += `<div class="file-item">${file.name} uploaded successfully! <a href="${response.file}" download>Download</a></div>`;
                    } else {
                        alert(response.message);
                    }
                    progressBarContainer.remove(); // Remove the progress bar after upload
                };

                xhr.send(formData);
            });
        });
    </script>
</body>
</html>