<?php

class bbQuote extends JBBCode\CodeDefinition {

    public function __construct()
    {
        parent::__construct();
        $this->setTagName("size");
        $this->useOption = false;
        $this->elCounter = 10;
        $this->parseContent = true;
    }

    public function asHtml(JBBCode\ElementNode $el)
    {
        $content = "";
        foreach($el->getChildren() as $child) {
            $content .= $child->getAsBBCode();
        }

        $foundMatch = preg_match('/\[size\=(.*?)\](.*?)\[\/size\]/is', $content, $matches);

        if(!$foundMatch)
            return $el->getAsBBCode();
        else {
          return '<blockquote>'.$matches[0].'</blockquote>';
         }
    }


}

?>