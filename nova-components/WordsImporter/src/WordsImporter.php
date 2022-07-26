<?php

namespace App\WordsImporter;

use Laravel\Nova\ResourceTool;

class WordsImporter extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Words Importer';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'words-importer';
    }
}
