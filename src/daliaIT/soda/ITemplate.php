<?php
namespace daliaIT\soda;
/**
 * Base class for soda code generators.
 * Provides methods for buffered text generation.
 * @author Oliver Anan <oliver@ananit.de>
 * @license MIT
 */

interface ITemplate {
    /**
     * Get the output of the template and clear all buffers
     */
    public function flush ();

    /**
     * get the string which used to indent a text 1 level
     */
    public function getIndent ();

    /**
     * set the string which used to indent a text 1 level
     */
    public function setIndent ($value);

    /**
     * Add raw text to the templte output.
     */
    public function write ($text='');

    /**
     * Add a indented line to the template Output
     */
    public function writeln ($text='', $indent='');

}
