<?php
// Get the exif data
$exif_data = exif_read_data( 'sample_images/_IGP8499.JPG' );
echo '<pre>';
print_r($exif_data);
echo '</pre>';
?>
