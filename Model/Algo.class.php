<?php

class Algo {
    private $data;
    private $valid = true;
    private $console = [];

    public function __construct($var)
    {
        $this->setData($var);
    }

    private function setData($data)
    {
        $this->data = trim($data);
    }

    private function getData()
    {
        return $this->data;
    }

    public function showResult()
    {
        $this->verifyFormat();
        $arr = $this->sData();
        if ($this->valid) {
            array_push($this->console, 'Expected result : '.eval("return $this->data;"));
            array_push($this->console, 'Calcul result : '.$this->calcul($arr));
        } else {
            array_push($this->console, 'error');
        }
        return $this->console;
    }

    private function verifyFormat()
    {
        if (preg_match("#[a-zA-Z]|[\+\*]{2,}#", $this->getData()) || in_array($this->getData()[-1], ['+', '-', '/', '*'])) {
            $this->valid = false;
        }

        if (preg_match("#\s{1,}#", $this->getData())) {
            $this->setData(preg_replace("#\s{1,}#", '', $this->getData()));
        }
    }

    // This function should not be here XD
    public function printArray($arr, $console, $sep = ' ')
    {
        $i = 0;
        while($i < count($arr)) {
            array_push($console, $arr[$i].$sep);
            $i++;
        }
    }

    private function sData()
    {
        $i = 0;
        $res = [];
        if (strlen($this->getData()) == 0) {
            $this->valid = false;
        } else if ($this->getData()[0] == '/' || $this->getData()[0] == '*') {
            $this->valid = false;
        }
        if (gettype($this->getData()) == 'string' && $this->valid) {
            while ($i < strlen($this->getData())) {
                if (!in_array($this->getData()[0], ['+', '-', '*', '/'])) {
                    if ($i > 0 && $i < strlen($this->getData()) && is_numeric($this->getData()[$i-1]) && !in_array($this->getData()[$i], ['+', '-', '*', '/'])) {
                        $res[count($res)-1] = $res[count($res)-1].$this->getData()[$i];
                    } else {
                        array_push($res, $this->getData()[$i]);
                    }
                }
                $i++;
            }
        }
        return $res;
    }

    private function multipleFois($data) {
        foreach($data as $key => $value) {
            if ($value == "*" && $key+1 <= count($data)) {
                $data[$key] = $this->fois($data[$key-1], $data[$key+1]);
                unset($data[$key-1]);
                unset($data[$key+1]);
            }
        }
        $data = array_values($data);

        return $data;
    }

    private function multipleDiv($data) {
        if (in_array('/', $data)) {
            $tmp = array_search('/', $data);
            $data[$tmp] = $this->div($data[$tmp-1], $data[$tmp+1]);
            unset($data[$tmp-1]);
            unset($data[$tmp+1]);
            $data = array_values($data);
        }
        if (in_array('/', $data)) {
            $this->multipleDiv($data);
        }

        return $data;
    }

    private function multipleMoins($data) {
        foreach($data as $key => $value) {
            if ($value == "-" && $key+1 <= count($data)) {
                $data[$key] = $this->neg($data[$key+1]);
                unset($data[$key+1]);
            }
        }
        $data = array_values($data);

        return $data;
    }

    private function multiplePlus($data) {
        if (in_array('+', $data)) {
            $tmp = array_search('+', $data);
            $data[$tmp] = $this->plus($data[$tmp-1], $data[$tmp+1]);
            unset($data[$tmp-1]);
            unset($data[$tmp+1]);
            $data = array_values($data);
        }
        if (in_array('+', $data)) {
            $this->multiplePlus($data);
        }

        return $data;
    }

    private function calcul($data) {
        $i = 0;
        $tmp1 = 0;
        $data = $this->multipleMoins($data);
        $data = $this->multipleDiv($data);
        $data = $this->multipleFois($data);
        $data = $this->multiplePlus($data);
        while ($i < count($data)) {
            $tmp1 = $this->plus($tmp1, $data[$i]);
            $i++;
        }
        return $tmp1;
    }

    public function plus($arg1, $arg2) {
        return (float)$arg1 + (float)$arg2;
    }

    public function fois($arg1, $arg2) {
        return (float)$arg1 * (float)$arg2;
    }

    public function div($arg1, $arg2) {
        return (float)$arg1 / (float)$arg2;
    }

    public function moins($arg1, $arg2) {
        return (float)$arg1 - (float)$arg2;
    }

    public function neg($arg) {
        return -1 * (int)$arg;
    }
}