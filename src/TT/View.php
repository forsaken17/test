<?php

namespace TT;

/**
 * rendering output
 *
 * @author tt
 */
class View {

    private $viewDir;

    public function __construct() {
        $this->viewDir = PROJ_ROOT . '/View/';
    }

    /**
     *
     * @param type $fileName
     * @param type $data
     * @return type
     */
    private function getTemplate($fileName, $data = []) {
        $filePath = $this->viewDir . $fileName . '.php';
        if (!is_readable($filePath)) {
            return $fileName . ' NOT FOUND';
        }
        foreach ($data as $key => $val) {
            $$key = $val; //extract($data);
        }
        include $filePath;
    }

    /**
     *
     * @param type $fileName
     * @param type $data
     * @param type $isComponent renders file without header and footer
     * @return type
     */
    public function render($fileName, $data, $isComponent = false) {
        ob_start();
        $isComponent? : $this->getTemplate('header');
        $this->getTemplate($fileName, $data);
        $isComponent? : $this->getTemplate('footer');
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}
