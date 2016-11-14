<?php

class AbstractController {
    public function checkFilters() {
        if (!isset($this->filters)) {
            return true;
        }


    }
}