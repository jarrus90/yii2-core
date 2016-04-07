<?php

namespace jarrus90\Core\Traits;

trait TextLineCleanupTrait {

    /**
     * Clean single line input
     * @param string $text raw input
     * @return string cleaned input
     */
    protected function cleanTextinput($text) {
        return htmlspecialchars(strip_tags(trim($text)));
    }
}