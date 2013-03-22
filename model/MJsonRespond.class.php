<?php
class MJsonRespond
{
	protected $result = array('code'=>null, 'msg'=>null, 'data'=>null);
	
    /**
     * json respond factory method
     *
     * @param int $code
     * @param string $msg
     * @param mix $data
     * @return MJsonRespond
     */
    public static function respond($code, $msg = null, $data = null)
    {
        $respond = new MJsonRespond($code, $msg, $data);
        return $respond;
    }

    public function __construct($code, $msg, $data)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }

    public function toJson()
    {
        return json_encode($this->result);
    }

    public function __set($key, $value)
    {
        assert(array_key_exists($key, $this->result));
        $this->result[$key] = $value;
        if ('code' === $key) {
            $errorMessage = Config::configForKeyPath('error');
            if (key_exists($value, $errorMessage)) {
               $this->msg = $errorMessage[$value];
            }
        }
    }

    public function __get($key)
    {
        assert(array_key_exists($key, $this->result));
        return $this->result[$key];	
    }
}
