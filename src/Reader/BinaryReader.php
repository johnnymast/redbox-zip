<?php

namespace Redbox\Zip\Reader;

class BinaryReader extends \SplFileObject
{

    const TEXT = 'text';
    const INT = 'int';
    const VALUE_OF = 'valueof';

    private $map = [];

    private $result = [];

    private $maxpos = 0;


    public function setMaxPos($max = 0)
    {
        $this->maxpos = $max;
    }


    /**
     * @return int
     */
    public function getMaxpos()
    {
        return $this->maxpos;
    }


    public function setMap($map = [])
    {
        $this->map = $map;
    }


    public function clearMap()
    {
        $this->map = [];
        $this->result = [];

        return $this;
    }


    private function clearResult()
    {
        foreach ($this->map as $key => $val) {
            $this->result[$key] = '';
        }
    }


    public function addMap($key = '', $type = self::TEXT, $size = 0)
    {
        $this->map[$key] = [
            'key'  => $key,
            'type' => $type,
            'size' => $size,
        ];

        return $this;
    }


    public function read()
    {
        if ($this->eof()) {
            return false;
        }

        $this->clearResult();

        foreach ($this->map as $key => $item) {
            $data = $this->fillMap($item['type'], $item['size']);

            if (strlen($data) > 0) {
                $this->result[$key] = $data;
            } else {
                return false;
            }
        }

        return $this->result;
    }


    private function fillMap($type = self::TEXT, $mixed = '')
    {
        $size = (int) $mixed;

        if ($type == self::VALUE_OF) {
            if ( ! isset($this->result[$mixed])) {
                return false;
            }

            $size = $this->result[$mixed];

            if ( ! $size) {
                return false;
            }
        }

        $pos = $this->ftell();

        $data = false;
        if ($size > 0 && ($pos + $size) <= $this->getMaxpos()) {
            $data = $this->fread($size);
        }

        if ($type == self::INT) {
            $data = intval(bin2hex($data), 16);;
        }

        return $data;
    }

}