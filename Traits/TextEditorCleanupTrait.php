<?php

namespace jarrus90\Core\Traits;

trait TextEditorCleanupTrait {

    /**
     * Clean rich text input
     * 
     * Strips tags excpept
     * br p hr table tr td ul ol lo span strong em del
     * Removes attributes except allowed in _safeAttributes
     * 
     * @param string $text raw input
     * @return string cleaned input
     */
    protected function cleanTextarea($text, $safeAttributes = ['style'], $safeTags = '', $overrideTags = false) {
        $safeTagsDefault = '<br><br/><p><hr><table><tr><td><ul><ol><li><span><strong><em><del><img><img/>';
        $str = nl2br(strip_tags($text, $safeTags . ($overrideTags ? '' : $safeTagsDefault)));
        $result = '';
        if (strlen($str) > 0) {
            $dom = new \DOMDocument();
            $dom->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $str);
            foreach ($dom->getElementsByTagName('*') as $element) {
                $attributes_to_remove = iterator_to_array($element->attributes);
                foreach ($safeAttributes AS $attributeItem) {
                    unset($attributes_to_remove[$attributeItem]);
                }
                foreach ($attributes_to_remove as $attribute => $value) {
                    $element->removeAttribute($attribute);
                }
            }
            foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
                $result .= $dom->saveHTML($node);
            }
        }
        return $result;
    }
}