<?php
declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2018 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Scrybe\Converter\Metadata;

/**
 * This collection manages which headings were discovered during the discovery phase and stores them as entries.
 */
class TableOfContents extends \ArrayObject
{
    /**
     * Contains the list of modules for this Table of Contents.
     *
     * @var TableOfContents\Module[]
     */
    protected $modules = [];

    public function offsetGet($index)
    {
        if (!isset($this[$index])) {
            $file = new TableOfContents\File();
            $file->setFilename($index);
            $this[] = $file;
        }

        return parent::offsetGet($index);
    }

    /**
     * Override offsetSet to force the use of the relative filename.
     *
     * @param void $index
     * @param TableOfContents\File $newval
     *
     * @throws \InvalidArgumentException if something other than a file is provided.
     */
    public function offsetSet($index, $newval)
    {
        if (!$newval instanceof TableOfContents\File) {
            throw new \InvalidArgumentException('A table of contents may only be filled with File objects');
        }

        $basename = basename($newval->getFilename());
        if (strpos($basename, '.') !== false) {
            $basename = substr($basename, 0, strpos($basename, '.'));
        }

        if (strtolower($basename) === 'index') {
            $this->modules[] = $newval;
        }

        parent::offsetSet($newval->getFilename(), $newval);
    }

    /**
     * Returns the list of modules.
     *
     * @return TableOfContents\Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }
}
