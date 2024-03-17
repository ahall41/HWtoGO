<?php

/**
 * Test Yaml format for combinations
 *
 * @author andrew
 */
class YamlTest {
    public static function dump(string $fn) {
        $yaml=yaml_parse_file(getenv("HOME") . "/GrandOrgue/Combinations/$fn");
        print_r($yaml);
    }
}

//YamlTest::dump("GrandOrgue demo V1/demo.yaml");
YamlTest::dump('Casavant, Bellevue, Demo (4ch)/cresc.yaml');
