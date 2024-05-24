<?php

namespace Modules\RadianEvent\Helpers;

use ZipArchive;

class ZipHelper
{
    public function extractZip($content, callable $filter = null)
    {
        $decode_content = $content;

        $temp = tempnam(sys_get_temp_dir(), time() . '.zip');
        file_put_contents($temp, $decode_content);
        $zip = new ZipArchive();
        $output = [];
        if (true === $zip->open($temp) && $zip->numFiles > 0) {
            $output = iterator_to_array($this->getFiles($zip, $filter));
        }
        $zip->close();
        unlink($temp);

        return $output;

    }

    private function getFiles(ZipArchive $zip, $filter)
    {
        $total = $zip->numFiles;
        for ($i = 0; $i < $total; ++$i) {
            $name = $zip->getNameIndex($i);
            if (false === $name) {
                continue;
            }

            if (!$filter || $filter($name)) {
                yield [
                    'filename' => $name,
                    'content' => $zip->getFromIndex($i),
                ];
            }
        }
    }
}
