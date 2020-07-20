<!DOCTYPE html>
<html>
<body>
<?php
//require_once __DIR__ . '/vendor/autoload.php';
?>
<div class='web_form form_horizontal'>
<form action="object-storage/upload" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
</div>
<?php
require_once __DIR__.'/samples/list.php';
?>
</body>
</html>
