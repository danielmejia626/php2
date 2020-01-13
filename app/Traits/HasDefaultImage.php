<?php


namespace App\Traits;


trait HasDefaultImage
{
    public function getImage($alText) {
        if (!$this->logo) {
            return "https://ui-avatars.com/api/?name=$alText&size=160";
        }
        return $this->logo;
    }
}

