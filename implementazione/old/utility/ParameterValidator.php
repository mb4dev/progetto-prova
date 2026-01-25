<?php

namespace utility;

class ParameterValidator
{
    /**
     * Validate required parameters
     * 
     * @param array $data The data to validate
     * @param array $required Array of required parameter names
     * @return array ['valid' => bool, 'missing' => array]
     */
    public function validateRequired(array $data, array $required): array
    {
        $missing = [];
        
        foreach ($required as $param) {
            if (!isset($data[$param]) || $data[$param] === '' || $data[$param] === null) {
                $missing[] = $param;
            }
        }
        
        return [
            'valid' => empty($missing),
            'missing' => $missing
        ];
    }

    /**
     * Validate parameter types
     * 
     * @param array $data The data to validate
     * @param array $types Associative array of parameter => expected type
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateTypes(array $data, array $types): array
    {
        $errors = [];
        
        foreach ($types as $param => $expectedType) {
            if (!isset($data[$param])) {
                continue; // Skip if parameter doesn't exist (handled by validateRequired)
            }
            
            $value = $data[$param];
            $actualType = gettype($value);
            
            // Special cases for flexible type checking
            switch ($expectedType) {
                case 'int':
                case 'integer':
                    if (!is_numeric($value) || (int)$value != $value) {
                        $errors[] = "Parameter '{$param}' must be an integer";
                    }
                    break;
                    
                case 'float':
                case 'double':
                    if (!is_numeric($value)) {
                        $errors[] = "Parameter '{$param}' must be a number";
                    }
                    break;
                    
                case 'string':
                    if (!is_string($value)) {
                        $errors[] = "Parameter '{$param}' must be a string";
                    }
                    break;
                    
                case 'bool':
                case 'boolean':
                    if (!is_bool($value) && !in_array($value, [0, 1, '0', '1', 'true', 'false'])) {
                        $errors[] = "Parameter '{$param}' must be a boolean";
                    }
                    break;
                    
                case 'array':
                    if (!is_array($value)) {
                        $errors[] = "Parameter '{$param}' must be an array";
                    }
                    break;
                    
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Parameter '{$param}' must be a valid email";
                    }
                    break;
                    
                case 'url':
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $errors[] = "Parameter '{$param}' must be a valid URL";
                    }
                    break;
                    
                default:
                    if ($actualType !== $expectedType) {
                        $errors[] = "Parameter '{$param}' must be of type {$expectedType}, {$actualType} given";
                    }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate parameter values against allowed values
     * 
     * @param array $data The data to validate
     * @param array $allowed Associative array of parameter => allowed values
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateAllowedValues(array $data, array $allowed): array
    {
        $errors = [];
        
        foreach ($allowed as $param => $allowedValues) {
            if (!isset($data[$param])) {
                continue;
            }
            
            $value = $data[$param];
            if (!in_array($value, $allowedValues, true)) {
                $errors[] = "Parameter '{$param}' must be one of: " . implode(', ', $allowedValues);
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate parameter length
     * 
     * @param array $data The data to validate
     * @param array $lengths Associative array of parameter => ['min' => int, 'max' => int]
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateLength(array $data, array $lengths): array
    {
        $errors = [];
        
        foreach ($lengths as $param => $rules) {
            if (!isset($data[$param])) {
                continue;
            }
            
            $value = $data[$param];
            $length = is_string($value) ? strlen($value) : (is_array($value) ? count($value) : 0);
            
            if (isset($rules['min']) && $length < $rules['min']) {
                $errors[] = "Parameter '{$param}' must be at least {$rules['min']} characters/items long";
            }
            
            if (isset($rules['max']) && $length > $rules['max']) {
                $errors[] = "Parameter '{$param}' must be at most {$rules['max']} characters/items long";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate numeric ranges
     * 
     * @param array $data The data to validate
     * @param array $ranges Associative array of parameter => ['min' => number, 'max' => number]
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateRanges(array $data, array $ranges): array
    {
        $errors = [];
        
        foreach ($ranges as $param => $range) {
            if (!isset($data[$param])) {
                continue;
            }
            
            $value = $data[$param];
            if (!is_numeric($value)) {
                continue; // Skip if not numeric (handled by validateTypes)
            }
            
            $numericValue = (float)$value;
            
            if (isset($range['min']) && $numericValue < $range['min']) {
                $errors[] = "Parameter '{$param}' must be at least {$range['min']}";
            }
            
            if (isset($range['max']) && $numericValue > $range['max']) {
                $errors[] = "Parameter '{$param}' must be at most {$range['max']}";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Perform comprehensive validation with multiple rules
     * 
     * @param array $data The data to validate
     * @param array $rules Validation rules
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validate(array $data, array $rules): array
    {
        $allErrors = [];
        
        // Validate required parameters
        if (isset($rules['required'])) {
            $result = $this->validateRequired($data, $rules['required']);
            if (!$result['valid']) {
                $allErrors = array_merge($allErrors, $result['missing']);
            }
        }
        
        // Validate types
        if (isset($rules['types'])) {
            $result = $this->validateTypes($data, $rules['types']);
            if (!$result['valid']) {
                $allErrors = array_merge($allErrors, $result['errors']);
            }
        }
        
        // Validate allowed values
        if (isset($rules['allowed'])) {
            $result = $this->validateAllowedValues($data, $rules['allowed']);
            if (!$result['valid']) {
                $allErrors = array_merge($allErrors, $result['errors']);
            }
        }
        
        // Validate length
        if (isset($rules['length'])) {
            $result = $this->validateLength($data, $rules['length']);
            if (!$result['valid']) {
                $allErrors = array_merge($allErrors, $result['errors']);
            }
        }
        
        // Validate ranges
        if (isset($rules['ranges'])) {
            $result = $this->validateRanges($data, $rules['ranges']);
            if (!$result['valid']) {
                $allErrors = array_merge($allErrors, $result['errors']);
            }
        }
        
        return [
            'valid' => empty($allErrors),
            'errors' => $allErrors
        ];
    }

    /**
     * Sanitize input data
     * 
     * @param array $data The data to sanitize
     * @return array Sanitized data
     */
    public function sanitize(array $data): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove HTML tags and extra whitespace
                $sanitized[$key] = trim(strip_tags($value));
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}