<?php
$exif = exif_read_data("IMG_0007.JPG");
$ort = $exif['IFD0']['Orientation'];
print_r ($exif);
switch($ort)
{
	case 1: // nothing
echo"1";
				break;

	case 2: // horizontal flip
		echo"2";
		break;

	case 3: // 180 rotate left
		echo"3";
		break;

	case 4: // vertical flip
		echo"4";
		break;

	case 5: // vertical flip + 90 rotate right
		echo"5";
		break;

	case 6: // 90 rotate right
		echo"6";
		break;

	case 7: // horizontal flip + 90 rotate right
		echo"7";
		break;

	case 8:    // 90 rotate left
		echo"8";
		break;
}
echo"fas";
?>