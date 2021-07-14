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

    public function showResult()
    {
        $arr = $this->sData();
        $arr = $this->pos($arr);
        $this->verifyFormat();
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
        if (preg_match("#[a-zA-Z]#", $this->data)) {
            $this->valid = false;
        }
    }

    // This function should not be here XD
    public function printArray($arr, $sep = ' ')
    {
        $i = 0;
        while($i < count($arr)) {
            array_push($this->console, $arr[$i].$sep);
            $i++;
        }
    }

    private function sData()
    {
        $i = 0;
        $res = [];
        if (strlen($this->data) == 0) {
            $this->valid = false;
        } else if ($this->data[0] == '/' || $this->data[0] == '*') {
            $this->valid = false;
        }
        if (gettype($this->data) == 'string' && $this->valid) {
            while ($i < strlen($this->data)) {
                if (!in_array($this->data[0], ['+', '-', '*', '/'])) {
                    if ($i > 0 && $i < strlen($this->data) && is_numeric($this->data[$i-1]) && !in_array($this->data[$i], ['+', '-', '*', '/'])) {
                        $res[count($res)-1] = $res[count($res)-1].$this->data[$i];
                    } else {
                        array_push($res, $this->data[$i]);
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

    private function pos($data)
    {
        foreach($data as $key => $value) {
            if (in_array($value, ['*', '/']) && in_array($data[$key-1], ['+', '/', '*'])) {
                $this->valid = false;
            }
            if ($value == '+' && $key+1 <= count($data) && in_array($data[$key+1], ['+', '/', '*'])) {
                $this->valid = false;
            }
            $data = array_values($data);
        }
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