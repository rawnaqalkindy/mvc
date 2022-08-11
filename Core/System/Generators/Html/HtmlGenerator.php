<?php

namespace Abc\System\Generators\Html;

class HtmlGenerator
{
    private string $result = '';

    private string $tag;
    private bool $closing_tag = true;
//    private string $content;

//    public function __construct(array $tag_data, string $content)
    public function __construct(array $tag_data)
    {
        $this->tag = strtolower($tag_data['tagname']);
        $this->closing_tag = $tag_data['closing_tag'];
//        $this->content = $content;
//        $this->generateTag();
    }

//    public function generateTag(): self
//    {
//        $this->result .= $this->opening_tag();
//        $this->result .= $this->appendContent();
//        $this->result .= $this->closing_tag();
////        echo $this->result;
//        return $this;
//    }

    public function changeTag(?string $new_tag = null): self
    {
        $this->tag = ($new_tag != '' && $new_tag != null) ? $new_tag : $this->tag;
        return $this;
    }

    public function opening_tag(): string
    {
        return '<' . $this->tag . '>';
    }

    public function closing_tag(): string
    {
        if ($this->closing_tag) {
            return '</' . $this->tag . '>';
        } else {
            return ' />';
        }
    }
}