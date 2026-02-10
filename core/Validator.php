<?php
/**
 * Form Validator Class
 */

class Validator {
    
    private $errors = [];
    
    /**
     * Validate required field
     * 
     * @param string $field Field name
     * @param mixed $value Field value
     * @param string $label Field label for error message
     * @return self
     */
    public function required($field, $value, $label) {
        if (empty($value) && $value !== '0') {
            $this->errors[$field] = "{$label} is required";
        }
        return $this;
    }
    
    /**
     * Validate email
     * 
     * @param string $field Field name
     * @param string $value Field value
     * @return self
     */
    public function email($field, $value) {
        if (!empty($value) && !Security::isValidEmail($value)) {
            $this->errors[$field] = "Invalid email address";
        }
        return $this;
    }
    
    /**
     * Validate phone number
     * 
     * @param string $field Field name
     * @param string $value Field value
     * @return self
     */
    public function phone($field, $value) {
        if (!empty($value) && !Security::isValidPhone($value)) {
            $this->errors[$field] = "Invalid phone number";
        }
        return $this;
    }
    
    /**
     * Validate minimum length
     * 
     * @param string $field Field name
     * @param string $value Field value
     * @param int $min Minimum length
     * @param string $label Field label
     * @return self
     */
    public function minLength($field, $value, $min, $label) {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters";
        }
        return $this;
    }
    
    /**
     * Validate maximum length
     * 
     * @param string $field Field name
     * @param string $value Field value
     * @param int $max Maximum length
     * @param string $label Field label
     * @return self
     */
    public function maxLength($field, $value, $max, $label) {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = "{$label} must not exceed {$max} characters";
        }
        return $this;
    }
    
    /**
     * Validate numeric value
     * 
     * @param string $field Field name
     * @param mixed $value Field value
     * @param string $label Field label
     * @return self
     */
    public function numeric($field, $value, $label) {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = "{$label} must be a number";
        }
        return $this;
    }
    
    /**
     * Validate date
     * 
     * @param string $field Field name
     * @param string $value Field value
     * @param string $label Field label
     * @return self
     */
    public function date($field, $value, $label) {
        if (!empty($value) && !Security::isValidDate($value)) {
            $this->errors[$field] = "{$label} must be a valid date (YYYY-MM-DD)";
        }
        return $this;
    }
    
    /**
     * Validate value matches another field
     * 
     * @param string $field Field name
     * @param mixed $value Field value
     * @param mixed $matchValue Value to match
     * @param string $label Field label
     * @return self
     */
    public function matches($field, $value, $matchValue, $label) {
        if ($value !== $matchValue) {
            $this->errors[$field] = "{$label} does not match";
        }
        return $this;
    }
    
    /**
     * Check if validation passed
     * 
     * @return bool
     */
    public function isValid() {
        return empty($this->errors);
    }
    
    /**
     * Get validation errors
     * 
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get first error message
     * 
     * @return string|null
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}
