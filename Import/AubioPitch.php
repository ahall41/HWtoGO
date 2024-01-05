<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Import;

/**
 * Detect pitch of a sample using aubiopitch
 *
 * @author andrew
 */
class AubioPitch {
    
    public static int $bufsize=10240;
    public static int $hopsize=10240;
    public static string $method="default";
    public static string $unit="midi";
    public static float $tolerance=0.9;
    public static float $silence=-90.0;
    public static float $startup=1.0;
    public static float $minval=1.0;
    
    public static function getPitch(string $filename) : float {
        $cmd=sprintf("aubiopitch -B %d -H %d -p %s -u %s -l %f -s %s '%s'",
                self::$bufsize, self::$hopsize, self::$method,
                self::$unit, self::$tolerance, self::$silence,
                $filename);
        $output=[];
        $rc=0;
        if (exec($cmd, $output, $rc)===FALSE) {
            error_log("'$cmd' failed: $rc");
            error_log(print_r($output,1));
            return -1.0;
        }
        
        $total=0.0;
        $nval=0;
        foreach ($output as $line) {
            $line=explode(" ", $line);
            $time=floatval($line[0]);
            $midi=floatval($line[1]);
            if ($time>self::$startup && $midi>self::$minval) {
                $total+=$midi;
                ++$nval;
            }
        }
        return $nval>0 ? $total/$nval : -2.0;
    }
}
