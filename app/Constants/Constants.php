<?php

namespace App\Constants;

abstract class Constants
{
     protected static $filePath;
     
     /**
      * Load json file.
      */
     public static function toArray($rules = null): array
     {
          $filePath = public_path() . static::$filePath;
          $data = json_decode(file_get_contents($filePath), true);

          if (isset($rules['getId'])) {
               foreach ($data as $key => $value) {
                    $data[$key] = $key + 1;
               }
          }

          return $data;
     }

	/**
      * Convert a given constant to a human readable form.
      */
     public static function toHuman($given): ?string
     {
          $data = collect(static::toArray());

          return $data->where('id', $given)->first()['name'] ?? NULL;
     }

     /**
      * Convert a given constant to an integer for storing.
      */
     public static function toMachine($given): ?int
     {
          $data = collect(static::toArray());

          return $data->where('id', $given)->first()['id'] ?? NULL;
     }
}
