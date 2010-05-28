<?php
/*
 * This file is part of Bender.
 *
 * (c) 2009-2010 Juan Carlos Clemente
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Modulo para generar los models de forma automática
 *
 */
class ModelController extends BenderController
{
    
    /**
     * Genera un schema a partir de la configuración en el archivo settings
     * @param string $schema [OPTIONAL] (generated)
     */
    public function generateSchemaAction()
    {
        $schemaGenerator = new SchemaGenerator();
        $schemaGenerator->generate($this->schema);
    }
    
    
}







