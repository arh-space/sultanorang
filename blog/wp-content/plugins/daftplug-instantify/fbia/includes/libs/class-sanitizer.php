<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyFbiaSanitizer')) {
    class daftplugInstantifyFbiaSanitizer {
        public function __construct() {

        }

        public function sanitize($html) {
            $dom = new DOMDocument;
            @$dom->loadHTML($html);
            $dom->normalizeDocument();
            $this->wrapFigureOpInteractive($dom, array('table','iframe'));
            $this->wrapFigure($dom, array('img', 'video'));            
            $this->removeAttributeByAttributeNames(array('style','target'), $dom);
            $this->removeElementsByTagNames($dom, array('style','script'));
            $this->removeTagKeepContent($dom, 'span');
            $this->removeTagKeepContent($dom, 'mark');
            $this->removeTagKeepContent($dom, 'div');
            $this->removeTagKeepContent($dom, 'data');
            $this->removeElementsfromParagraph($dom, array('figure'));
            $this->removeEmptyElements($dom);
            $this->replaceElements($dom, 'h1', 'h2');
            $this->replaceElements($dom, 'h3', 'h2');
            $this->replaceElements($dom, 'h4', 'h2');
            
            $body = $dom->documentElement->lastChild;
            preg_match("/<body[^>]*>(.*?)<\/body>/is", $dom->saveHTML($body), $matches);
            return $matches[1];
        }

        private function removeElementsByTagNames($dom, $tagNames) {
            foreach($tagNames as $tagName) {
                $nodeList = $dom->getElementsByTagName($tagName);
                for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0;) {
                    $node = $nodeList->item($nodeIdx);
                    $node->parentNode->removeChild($node);
                }
            }
        }

        private function removeTagKeepContent($dom, $tagName) {
            $nodes = $dom->getElementsByTagName($tagName);
            while ($node = $nodes->item(0)) {
                $replacement = $dom->createDocumentFragment();
                while ($inner = $node->childNodes->item(0)) {
                    $replacement->appendChild($inner);
                }
                $node->parentNode->replaceChild($replacement, $node);
            }
        }

        private function removeAttributeByAttributeNames($attributeNames, $dom) {
            foreach($attributeNames as $attributeName) {
                foreach($dom->getElementsByTagName('*') as $element) {
                    if ($element->getAttribute($attributeName)) {
                        $element->removeAttribute($attributeName);
                    }
                }
            }
        }

        private function wrapFigure($dom, $elements) {
            foreach ($elements as $element) {
                $elementTags = $dom->getElementsByTagName($element);
                foreach ($elementTags as $elementTag) {
                	if ($elementTag->parentNode->tagName == 'figure') {
                		$elementTag->parentNode->parentNode->replaceChild($elementTag, $elementTag->parentNode);
                	}
		        	$figure = $dom->createElement('figure');
					$elementTag->parentNode->replaceChild($figure, $elementTag);
					$figure->appendChild($elementTag);
                }
            }
        }

        private function wrapFigureOpInteractive($dom, $elements) {
            foreach ($elements as $element) {
                $elementTags = $dom->getElementsByTagName($element);
                foreach ($elementTags as $elementTag) {
                	if ($elementTag->parentNode->tagName == 'figure') {
                		$elementTag->parentNode->parentNode->replaceChild($elementTag, $elementTag->parentNode);
                	}
		        	$figure = $dom->createElement('figure');
		        	$figure->setAttribute('class', 'op-interactive');
					$elementTag->parentNode->replaceChild($figure, $elementTag);
					if ($elementTag->tagName !== 'iframe') {
						$iframe = $dom->createElement('iframe');
				    	$iframe->setAttribute('class', 'no-margin');
				    	$iframe->appendChild($elementTag);
				    	$figure->appendChild($iframe);
					} else {
						$figure->appendChild($elementTag);
					}
                }
            }
        }

        private function removeElementsfromParagraph($dom, $tags) {
            foreach ($tags AS $tag) {
                $elements = $dom->getElementsByTagName($tag);
                foreach ($elements AS $element) {
                    $parent = $element->parentNode;
                    $grandparent = $parent->parentNode;
                    if ($parent->nodeName == 'p') {
                        $clone = $element->cloneNode(true);
                        $grandparent->insertBefore($clone, $parent); 
                        $parent->removeChild($element);
                    }
                }
            }
        }

        private function removeEmptyElements($dom) {
            $elems = array('span','p','h1');
            foreach($elems as $elem) {
                $domNodeList = $dom->getElementsByTagname($elem);
                $domElemsToRemove = array();
                foreach ($domNodeList as $domElement) {
                    $domElement->normalize();
                    if (trim($domElement->textContent, "\xc2\xa0 \n \t ") == "") {
                        $domElemsToRemove[] = $domElement;
                    }
                }
                foreach( $domElemsToRemove as $domElement ){
                    try {
                        $domElement->parentNode->removeChild($domElement);
                    } catch (Exception $e) {

                    }
                }
            }
        }

        private function replaceElements($dom, $find, $replace) {
            $elements = $dom->getElementsByTagName($find);
            for ($i = $elements->length - 1; $i >= 0; $i --) {
                $nodePre = $elements->item($i);
                $nodeDiv = $dom->createElement($replace, htmlspecialchars($nodePre->nodeValue));
                $nodePre->parentNode->replaceChild($nodeDiv, $nodePre);
            }
        }
    }
}