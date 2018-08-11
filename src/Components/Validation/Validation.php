<?php

namespace Nur\Components\Validation;

use Nur\Facades\Http;

class Validation
{
    /**
     * Validation errors
     * 
     * @var array
     */
    protected $errors 	= [];

    /**
     * Label for fields
     * 
     * @var array
     */
    protected $labels	= [];

    /**
     * Validation rules
     * 
     * @var array
     */
    protected $rules 	= [];

    /**
     * Data to validate
     * 
     * @var array
     */
    protected $data		= [];

    /**
     * Fields validation error messagses
     * 
     * @var array
     */
    protected $texts	= [];
    
    /**
     * Default validation error message
     * 
     * @var string
     */
    protected $msg      = '%s is not valid.';


    /**
     * Define Validation Rules
     *
     * @param array $rules
     * @return void
     */
    public function rules(Array $rules)
    {
        foreach ($rules as $key => $value) {
            $this->rule(
                $key, $value['label'], 
                $value['rules'], 
                isset($value['text']) && !empty($value['text']) ? $value['text'] : []
            );
        }
    }

    /**
     * Define One Validation Rule
     *
     * @param string $field
     * @param string $label
     * @param string $rules
     * @return void 
     */
    public function rule($field, $label, $rules, Array $text = [])
    {
        $this->labels[$field] = $label;
        $this->rules[$field]  = $rules;
        $this->texts[$field]  = (!empty($text) ? $text : null);
    }

    /**
     * Validate
     *
     * @param array $data
     * @return boolean
     */
    public function isValid(Array $data = [])
    {
        if(empty($data)) {
            $data = Http::method() == 'GET' ? Http::get() : Http::post();
        }
        $this->data = $data;

        foreach ($this->rules as $key => $value) {
            $rules = explode('|', $value);
            foreach ($rules as $rule) {
                if (strpos($rule, ',')) {
                    $group  = explode(',', $rule);
                    $filter = $group[0];
                    $params = $group[1];
                    $this->errorMessage($filter, $key, $params);
                } else {
                    $this->errorMessage($rule, $key);
                }
            }
        }
        
        $this->errors = array_values(array_unique($this->errors));
        if (count($this->errors) > 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Field validation error messages
     * 
     * @param string $filter
     * @param string $field
     * @param string $params 
     * @return void
     */
    protected function errorMessage($filter, $field, $params = null)
    {
        $text = (isset($this->texts[$field][$filter]) && !is_null($this->texts[$field][$filter]) 
                ? $this->texts[$field][$filter] : $this->msg);
        $text = str_replace([':label:', ':value:'], '%s', $text);

        if (! isset($this->data[$field])) {
            $this->errors[] = sprintf($text, $this->labels[$field], $params);
        } elseif (! is_null($params)) {
            if ($filter == 'matches') {
                if ($this->matches($this->data[$field], $this->data[$params]) === false) {
                    $this->errors[] = sprintf($text, $this->labels[$field], $params);
                }
            } else {
                if ($this->$filter($this->data[$field], $params) === false) {
                    $this->errors[] = sprintf($text, $this->labels[$field], $params);
                }
            }
        } else {
            if ($this->$filter($this->data[$field]) === false) {
                $this->errors[] = sprintf($text, $this->labels[$field], $params);
            }
        }
    }

    /**
     * Sanitizing Data
     *
     * @param string $data
     * @return string
     */
    public function sanitize($data)
    {
        if (! is_array($data)) {
            return filter_var(trim($data), FILTER_SANITIZE_STRING);
        } 
        
        foreach ($data as $key => $value) {
            $data[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        }
        return $data;
    }

    /**
     * Return errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Required Field Control
     *
     * @param string $data
     * @return boolean
     */
    protected function required($data)
    {
        return (!empty($data) && !is_null($data) && $data !== '');
    }

    /**
     * Numeric Field Control
     *
     * @param int $data
     * @return boolean
     */
    protected function numeric($data)
    {
        return (is_numeric($data));
    }

    /**
     * Email Validation
     *
     * @param string $email
     * @return boolean
     */
    protected function email($email)
    {
        return (filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    /**
     * Minimum Character Check
     *
     * @param string $data
     * @param int $length
     * @return boolean
     */
    protected function min_len($data, $length)
    {
        return (strlen($data) >= $length);
    }

    /**
     * Maximum Character Check
     *
     * @param string $data
     * @param int $length
     * @return boolean
     */
    protected function max_len($data, $length)
    {
        return (strlen($data) <= $length);
    }

    /**
     * Exact Length Check
     *
     * @param string $data
     * @param int $length
     * @return boolean
     */
    protected function exact_len($data, $length)
    {
        return (strlen($data) == $length);
    }

    /**
     * Alpha Character Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function alpha($data)
    {
        return (preg_match('/^[a-zA-ZÇçĞğİıÖöŞşÜü]+$/i', $data)
                ? true : ctype_alpha($data));
    }

    /**
     * Alphanumeric Character Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function alpha_num($data)
    {
        return (preg_match('/^[0-9a-zA-ZÇçĞğİıÖöŞşÜü]+$/i', $data)
                ? true : ctype_alnum($data));
    }

    /**
     * Alpha-dash Character Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function alpha_dash($data)
    {
        return !(!preg_match("/^([A-Za-z0-9_-])+$/i", $data));
    }

    /**
     * Alpha-space Character Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function alpha_space($data)
    {
        return !(!preg_match("/^([A-Za-z0-9- ])+$/i", $data));
    }

    /**
     * Integer Validation
     *
     * @param int $data
     * @return boolean
     */
    protected function integer($data)
    {
        return !(!preg_match("/^([0-9])+$/i", $data));
    }

    /**
     * Boolean Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function boolean($data)
    {
        return ($data === true || $data === false);
    }

    /**
     * Float Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function float($data)
    {
        return (!preg_match("/^([0-9\.])+$/i", $data)) ? false : true;
    }

    /**
     * URL Validation
     *
     * @param string $url
     * @return boolean
     */
    protected function valid_url($url)
    {
        return (filter_var($url, FILTER_VALIDATE_URL));
    }

    /**
     * IP Validation
     *
     * @param string $ip
     * @return boolean
     */
    protected function valid_ip($ip)
    {
        return (filter_var($ip, FILTER_VALIDATE_IP));
    }

    /**
     * IPv4 Validation
     *
     * @param string $ip
     * @return boolean
     */
    protected function valid_ipv4($ip)
    {
        return (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4));
    }

    /**
     * IPv6 Validation
     *
     * @param string $ip
     * @return boolean
     */
    protected function valid_ipv6($ip)
    {
        return (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6));
    }

    /**
     * Credit Card Validation
     *
     * @param string $data
     * @return boolean
     */
    protected function valid_cc($data)
    {
        $number = preg_replace('/\D/', '', $data);
        if (function_exists('mb_strlen')) {
            $number_length = mb_strlen($number);
        } else {
            $number_length = strlen($number);
        }

        $parity = $number_length % 2;
        $total = 0;
        for ($i=0; $i<$number_length; $i++) {
            $digit = $number[$i];

            if ($i % 2 == $parity) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $total += $digit;
        }

        return ($total % 10 == 0) ? true : false;
    }

    /**
     * Field must contain something
     *
     * @param string $data
     * @param string $part
     * @return boolean
     */
    protected function contains($data, $part)
    {
        return (strpos($data, $part) !== false);
    }

    /**
     * Minimum Value Validation
     *
     * @param int $data
     * @param int $min
     * @return boolean
     */
    protected function min_numeric($data, $min)
    {
        return (is_numeric($data) && is_numeric($min) && $data >= $min);
    }

    /**
     * Maximum Value Validation
     *
     * @param int $data
     * @param int $max
     * @return boolean
     */
    protected function max_numeric($data, $max)
    {
        return (is_numeric($data) && is_numeric($max) && $data <= $max);
    }

    /**
     * Matched Fields Validation
     *
     * @param string $data
     * @param string $field
     * @return bool
     */
    protected function matches($data, $field)
    {
        return ($data == $field);
    }
}