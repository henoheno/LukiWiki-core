<?php
/**
 * 見出しクラス.
 *
 * @author    Logue <logue@hotmail.co.jp>
 * @copyright 2013-2014,2018 Logue
 * @license   MIT
 */

namespace Logue\LukiWiki\Element;

use Logue\LukiWiki\AbstractElement;
use Logue\LukiWiki\Rules\HeadingAnchor;

/**
 * * Heading1
 * ** Heading2
 * *** Heading3
 * **** Heading4
 * ***** Heading5.
 */
class Heading extends Element
{
    protected $level;
    protected $id;
    protected $text;

    public function __construct(object $root, string $text, bool $isAmp)
    {
        parent::__construct();

        $this->level = min(5, strspn($text, '*'));
        list($text, $this->msg_top, $this->id) = $root->getAnchor($text, $this->level);

        $content = new InlineElement($text, $isAmp);
        $this->meta = $content->getMeta();
        $this->insert($content);

        ++$this->level; // h2,h3,h4,h5,h6
    }

    public function insert(object $obj)
    {
        parent::insert($obj);

        return $this->last = $this;
    }

    public function __toString()
    {
        list($this->text, $fixed_anchor) = HeadingAnchor::get($this->text, false);
        $id = (empty($fixed_anchor)) ? $this->id : $fixed_anchor;

        $this->meta[$id] = $this->text;

        return $this->wrap(parent::__toString(), 'h'.$this->level, ['id' => $id], false);
    }
}
