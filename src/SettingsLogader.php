<?php

/**
 * Copyright 2025 hacknow.blog
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
