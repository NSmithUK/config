<?php

namespace Noodlehaus\File;

use Exception;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Noodlehaus\Exception\ParseException;

/**
 * YAML file loader
 *
 * @package    Config
 * @author     Jesus A. Domingo <jesus.domingo@gmail.com>
 * @author     Hassan Khan <contact@hassankhan.me>
 * @link       https://github.com/noodlehaus/config
 * @license    MIT
 */
class Yaml implements FileInterface
{
    /**
     * {@inheritDoc}
     * Loads a YAML/YML file as an array
     *
     * @throws ParseException If If there is an error parsing the YAML file
     */
    public function load($path)
    {
        try {
            $data = YamlParser::parse($path);
        } catch (Exception $exception) {
            throw new ParseException(
                array(
                    'message'   => 'Error parsing YAML file',
                    'exception' => $exception,
                )
            );
        }

        return $data;
    }
}
