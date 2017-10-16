<?php

namespace SM\SampleServiceContract\Api\Data;

interface SampleInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID      = 'id';
    const CONTENT    = 'content';
  
    /**#@-*/

    
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

 
    /**
     * Set ID
     *
     * @param int $id
     * @return SampleInterface
     */
    public function setId($id);

    /**
     * Set ID
     *
     * @param string $content
     * @return SampleInterface
     */
    public function setContent($content);
    
}
