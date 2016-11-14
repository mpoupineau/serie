<?php

namespace FM\FileManagementBundle\FileM;
use ZipArchive;

class FileM
{
    public function resizeFile($pathOriginFile, $pathFinalFile) {
        // Calcul des nouvelles dimensions
        list($width, $height) = getimagesize($pathOriginFile);
        $new_width = '300';
        $new_height = '411';

        // Redimensionnement
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($pathOriginFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $pathFinalFile);
    }
    
	public function getContentsFromFile($url)
	{
		return file_get_contents($url);
	}
	
	public function uploadFile($url, $file)
	{
		return file_put_contents($file, $this->getContentsFromFile($url));
	}
	
	public function extractZip($file, $repository)
	{
		$zip = new ZipArchive;
		if ($zip->open($file) === TRUE) {
			$zip->extractTo($repository);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}

}