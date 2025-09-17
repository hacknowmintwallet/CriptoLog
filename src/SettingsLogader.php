<?php

declare(strict_types=1);

namespace HackNOW\CriptLog;

class SettingsLoader
{
    public static function load(string $path): array
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Settings file not found: $path");
        }
        
        $settings = require $path;
        
        if (!is_array($settings)) {
            throw new \RuntimeException("Settings file must return an array");
        }
        
        // Override with environment variables if available
        foreach ($settings as $key => $value) {
            $envValue = getenv(strtoupper($key));
            if ($envValue !== false) {
                $settings[$key] = $envValue;
            }
        }
        
        return $settings;
    }
}