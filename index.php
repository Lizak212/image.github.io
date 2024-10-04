<?php 
  $images = "images/";
  $data = "data.json";

  if (!is_dir ($images)) {
    mkdir ($images, 0777, true);
  }

  if (!file_exists ($data)) {
    file_put_contents ($data, json_encode ([]));
  }

  if (isset ($_POST ["upload"])) {
    $name = $_POST ["name"];
    $file = basename ($_FILES ["file"]["name"]);

    move_uploaded_file ($_FILES ["file"]["tmp_name"], $images . $file);

    $file_name = json_decode (file_get_contents ($data), true);

    $current_image = [
      'timestamp' => date('Y-m-d H:i:s'),
      'name' => $name,
      'file' => $file 
    ];

    $file_name [] = $current_image;

    file_put_contents ($data, json_encode ($file_name, JSON_PRETTY_PRINT));
  }

  $gallery_images = json_decode (file_get_contents ($data), true);
?>

<html>
<head>
  <title>Image Gallery</title>

  <style>
    body {
      display: flex; 
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .gallery {
      width: 100%;
      max-width: 800px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
    }

    .gallery-item {
      text-align: center;
      background-color: white;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
  
<body>

  <h1>Image Gallery</h1>

  <!-- this line was the issue, enctype = "multipart/form-data" is needed to upload images -->
  <form method = "post" enctype = "multipart/form-data"> 
    <label> Your Name: </label>
    <input type = "text" name = "name" required>

    <label> Upload Image: </label>
    <input type = "file" name = "file" accept = "image/*" required>

    <button name = "upload"> Upload </button>
  </form>

  <div class = "gallery">
    <?php if (empty ($gallery_images)): ?>
      <p>No images</p>
    <?php else: ?>
      <?php foreach ($gallery_images as $image): ?>
        <div class = "gallery-item">
          <img src = "<?php echo $images . $image['file']; ?>">
          <p> Uploaded by: <?php echo $image['name']; ?></p>
          <p> Uploaded on: <?php echo $image['timestamp']; ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</body>
</html>
